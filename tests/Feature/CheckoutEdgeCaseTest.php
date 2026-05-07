<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CheckoutEdgeCaseTest extends TestCase
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

    protected function createPaymentMethod(string $name = 'Cash on Delivery'): PaymentMethod
    {
        return PaymentMethod::create(['methodName' => $name]);
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

    protected function createVoucher(array $overrides = []): Voucher
    {
        return Voucher::create(array_merge([
            'voucherName' => 'SAVE10',
            'voucherType' => 'flat',
            'voucherAmount' => 10,
            'voucherUsed' => 0,
            'is_active' => true,
        ], $overrides));
    }

    protected function createOrder(Customer $customer, Cart $cart, PaymentMethod $paymentMethod, ?Voucher $voucher = null): Order
    {
        return Order::create([
            'order_status' => 'Pending',
            'order_date' => now()->toDateString(),
            'total_price' => 100,
            'voucher_id' => $voucher?->voucher_id,
            'add_id' => $customer->address?->add_id,
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
            'cus_id' => $customer->cus_id,
            'cart_id' => $cart->cart_id,
        ]);
    }

    public function test_checkout_rejects_invalid_voucher_code(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProduct();
        $this->createActiveCartWithItem($customer, $product, 1);

        $this->actingAs($customer, 'customer');

        $response = $this->get('/checkout/address?voucher_code=NOPE');

        $response->assertRedirect('/cart');
        $response->assertSessionHasErrors('voucher_code');
    }

    public function test_checkout_rejects_inactive_voucher(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProduct();
        $this->createActiveCartWithItem($customer, $product, 1);

        $voucher = $this->createVoucher([
            'voucherName' => 'INACTIVE',
            'is_active' => false,
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->get('/checkout/address?voucher_code=' . $voucher->voucherName);

        $response->assertRedirect('/cart');
        $response->assertSessionHasErrors('voucher_id');
    }

    public function test_checkout_rejects_voucher_below_minimum_order(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProduct(['Price' => 50]);
        $this->createActiveCartWithItem($customer, $product, 1);

        $voucher = $this->createVoucher([
            'voucherName' => 'MIN100',
            'minimum_order_amount' => 100,
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->get('/checkout/address?voucher_code=' . $voucher->voucherName);

        $response->assertRedirect('/cart');
        $response->assertSessionHasErrors('voucher_id');
    }

    public function test_checkout_rejects_voucher_when_max_uses_reached(): void
    {
        $customer = $this->createCustomer();
        $product = $this->createProduct();
        $this->createActiveCartWithItem($customer, $product, 1);

        $voucher = $this->createVoucher([
            'voucherName' => 'MAXED',
            'max_uses' => 1,
            'voucherUsed' => 1,
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->get('/checkout/address?voucher_code=' . $voucher->voucherName);

        $response->assertRedirect('/cart');
        $response->assertSessionHasErrors('voucher_id');
    }

    public function test_checkout_rejects_voucher_when_per_customer_limit_reached(): void
    {
        $customer = $this->createCustomer();
        $address = $this->createAddress($customer);
        $paymentMethod = $this->createPaymentMethod();

        $voucher = $this->createVoucher([
            'per_customer_limit' => 1,
        ]);

        $product = $this->createProduct(['Stock' => 5]);
        $cart = $this->createActiveCartWithItem($customer, $product, 1);

        $this->createOrder($customer, $cart, $paymentMethod, $voucher);

        $cart->update(['status' => 'checked_out']);
        $newCart = $this->createActiveCartWithItem($customer, $product, 1);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
            'add_id' => $address->add_id,
            'voucher_id' => $voucher->voucher_id,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('voucher_id');
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseMissing('orders', [
            'cart_id' => $newCart->cart_id,
        ]);
    }

    public function test_checkout_requires_payment_proof_for_gcash(): void
    {
        $customer = $this->createCustomer();
        $address = $this->createAddress($customer);
        $paymentMethod = $this->createPaymentMethod('GCash');
        $product = $this->createProduct();
        $this->createActiveCartWithItem($customer, $product, 1);

        $this->actingAs($customer, 'customer');

        $response = $this->withSession(['checkout.add_id' => $address->add_id])
            ->post('/checkout/payment', [
                'paymentMethod_id' => $paymentMethod->paymentMethod_id,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['payment_reference', 'payment_proof']);
    }

    public function test_payment_proof_is_removed_when_checkout_fails(): void
    {
        Storage::fake('public');

        $customer = $this->createCustomer();
        $address = $this->createAddress($customer);
        $paymentMethod = $this->createPaymentMethod('GCash');
        $product = $this->createProduct();
        $this->createActiveCartWithItem($customer, $product, 1);

        $voucher = $this->createVoucher([
            'voucherName' => 'INACTIVE',
            'is_active' => false,
        ]);

        $this->actingAs($customer, 'customer');

        $response = $this->withSession([
                'checkout.add_id' => $address->add_id,
                'checkout.voucher_id' => $voucher->voucher_id,
            ])
            ->post('/checkout/payment', [
                'paymentMethod_id' => $paymentMethod->paymentMethod_id,
                'payment_reference' => 'GC-12345',
                'payment_proof' => UploadedFile::fake()->image('proof.jpg'),
            ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHasErrors('voucher_id');
        $this->assertCount(0, Storage::disk('public')->allFiles('payment-proofs'));
    }

    public function test_checkout_fails_when_stock_is_insufficient(): void
    {
        $customer = $this->createCustomer();
        $address = $this->createAddress($customer);
        $paymentMethod = $this->createPaymentMethod();
        $product = $this->createProduct(['Stock' => 1]);
        $this->createActiveCartWithItem($customer, $product, 2);

        $this->actingAs($customer, 'customer');

        $response = $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
            'add_id' => $address->add_id,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors('cart');
        $this->assertDatabaseCount('orders', 0);
    }
}
