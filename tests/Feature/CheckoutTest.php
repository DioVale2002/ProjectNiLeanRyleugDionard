<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function createCustomer(array $overrides = []): Customer
    {
        return Customer::create(array_merge([
            'first_name'  => 'John',
            'last_name'   => 'Doe',
            'contact_num' => '09123456789',
            'email'       => 'john' . uniqid() . '@example.com',
            'password'    => Hash::make('password123'),
        ], $overrides));
    }

    protected function createProduct(array $overrides = []): Product
    {
        return Product::create(array_merge([
            'Title'     => 'Test Book',
            'Author'    => 'Test Author',
            'Price'     => 299.00,
            'Stock'     => 10,
            'ISBN'      => '978-' . rand(1000000000, 9999999999),
            'Publisher' => 'Test Publisher',
            'Genre'     => 'Fiction',
        ], $overrides));
    }

    protected function createPaymentMethod(): PaymentMethod
    {
        return PaymentMethod::create(['methodName' => 'Cash on Delivery']);
    }

    protected function createAddress(Customer $customer, array $overrides = []): Address
    {
        return Address::create(array_merge([
            'cus_id'          => $customer->cus_id,
            'country'         => 'Philippines',
            'province'        => 'Davao del Sur',
            'city'            => 'Davao City',
            'barangay'        => 'Poblacion',
            'zip_postal_code' => '8000',
        ], $overrides));
    }

    protected function createActiveCartWithItem(Customer $customer, Product $product, int $quantity): Cart
    {
        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => $quantity,
            'unitPrice'  => $product->Price,
            'subtotal'   => $product->Price * $quantity,
        ]);

        return $cart;
    }

    public function test_order_rejects_address_not_owned(): void
    {
        $customer = $this->createCustomer();
        $otherCustomer = $this->createCustomer(['email' => 'other' . uniqid() . '@example.com']);

        $otherAddress = $this->createAddress($otherCustomer);
        $paymentMethod = $this->createPaymentMethod();
        $product = $this->createProduct(['Stock' => 5]);
        $this->createActiveCartWithItem($customer, $product, 1);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
            'add_id' => $otherAddress->add_id,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('add_id');
        $this->assertDatabaseMissing('orders', [
            'cus_id' => $customer->cus_id,
        ]);
    }

    public function test_order_creates_stock_out_with_quantity(): void
    {
        $customer = $this->createCustomer();
        $address = $this->createAddress($customer);
        $paymentMethod = $this->createPaymentMethod();
        $product = $this->createProduct(['Stock' => 10]);
        $this->createActiveCartWithItem($customer, $product, 2);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
            'add_id' => $address->add_id,
        ]);

        $response->assertRedirect('/account/orders');
        $this->assertDatabaseHas('orders', [
            'cus_id' => $customer->cus_id,
            'add_id' => $address->add_id,
        ]);
        $this->assertDatabaseHas('stock_out', [
            'productOut' => $product->product_ID,
            'quantity' => 2,
        ]);
        $this->assertDatabaseHas('products', [
            'product_ID' => $product->product_ID,
            'Stock' => 8,
        ]);
    }
}
