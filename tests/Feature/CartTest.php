<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function createCustomer()
    {
        return Customer::create([
            'first_name'  => 'John',
            'last_name'   => 'Doe',
            'contact_num' => '09123456789',
            'email'       => 'john@example.com',
            'password'    => Hash::make('password123'),
        ]);
    }

    protected function createProduct(array $overrides = [])
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

    protected function createPaymentMethod()
    {
        return PaymentMethod::create(['methodName' => 'Cash on Delivery']);
    }

    // -------------------------------------------------------
    // CART ACCESS
    // -------------------------------------------------------

    public function test_guest_cannot_view_cart()
    {
        $response = $this->get('/cart');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_customer_can_view_cart()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->get('/cart');
        $response->assertStatus(200);
        $response->assertViewIs('cart.index');
    }

    public function test_empty_cart_shows_empty_message()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->get('/cart');
        $response->assertSee('Your cart is empty');
    }

    // -------------------------------------------------------
    // ADD TO CART
    // -------------------------------------------------------

    public function test_guest_cannot_add_to_cart()
    {
        $product = $this->createProduct();

        $response = $this->post('/cart/add', [
            'product_ID' => $product->product_ID,
            'quantity'   => 1,
        ]);

        $response->assertRedirect('/login');
    }

    public function test_customer_can_add_product_to_cart()
    {
        $customer = $this->createCustomer();
        $product  = $this->createProduct(['Stock' => 10]);
        $this->actingAs($customer, 'customer');

        $response = $this->post('/cart/add', [
            'product_ID' => $product->product_ID,
            'quantity'   => 2,
        ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('success', 'Item added to cart.');

        $this->assertDatabaseHas('cart_items', [
            'product_ID' => $product->product_ID,
            'quantity'   => 2,
        ]);
    }

    public function test_adding_same_product_increments_quantity()
    {
        $customer = $this->createCustomer();
        $product  = $this->createProduct(['Stock' => 10]);
        $this->actingAs($customer, 'customer');

        $this->post('/cart/add', [
            'product_ID' => $product->product_ID,
            'quantity'   => 2,
        ]);

        $this->post('/cart/add', [
            'product_ID' => $product->product_ID,
            'quantity'   => 3,
        ]);

        $this->assertDatabaseHas('cart_items', [
            'product_ID' => $product->product_ID,
            'quantity'   => 5,
        ]);
    }

    public function test_cannot_add_more_than_available_stock()
    {
        $customer = $this->createCustomer();
        $product  = $this->createProduct(['Stock' => 3]);
        $this->actingAs($customer, 'customer');

        $response = $this->post('/cart/add', [
            'product_ID' => $product->product_ID,
            'quantity'   => 10,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('cart_items', [
            'product_ID' => $product->product_ID,
        ]);
    }

    public function test_cannot_add_nonexistent_product()
    {
        $customer = $this->createCustomer();
        $this->actingAs($customer, 'customer');

        $response = $this->post('/cart/add', [
            'product_ID' => 9999,
            'quantity'   => 1,
        ]);

        $response->assertSessionHasErrors('product_ID');
    }

    // -------------------------------------------------------
    // UPDATE CART
    // -------------------------------------------------------

    public function test_customer_can_update_cart_item_quantity()
    {
        $customer = $this->createCustomer();
        $product  = $this->createProduct(['Stock' => 10, 'Price' => 100]);
        $this->actingAs($customer, 'customer');

        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        $item = CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => 1,
            'unitPrice'  => 100,
            'subtotal'   => 100,
        ]);

        $response = $this->patch("/cart/update/{$item->cartitems_id}", [
            'quantity' => 3,
        ]);

        $response->assertRedirect('/cart');
        $this->assertDatabaseHas('cart_items', [
            'cartitems_id' => $item->cartitems_id,
            'quantity'     => 3,
            'subtotal'     => 300,
        ]);
    }

    public function test_cannot_update_cart_item_beyond_stock()
    {
        $customer = $this->createCustomer();
        $product  = $this->createProduct(['Stock' => 2, 'Price' => 100]);
        $this->actingAs($customer, 'customer');

        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        $item = CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => 1,
            'unitPrice'  => 100,
            'subtotal'   => 100,
        ]);

        $response = $this->patch("/cart/update/{$item->cartitems_id}", [
            'quantity' => 99,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    // -------------------------------------------------------
    // REMOVE FROM CART
    // -------------------------------------------------------

    public function test_customer_can_remove_item_from_cart()
    {
        $customer = $this->createCustomer();
        $product  = $this->createProduct();
        $this->actingAs($customer, 'customer');

        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        $item = CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => 1,
            'unitPrice'  => $product->Price,
            'subtotal'   => $product->Price,
        ]);

        $response = $this->delete("/cart/remove/{$item->cartitems_id}");

        $response->assertRedirect('/cart');
        $this->assertDatabaseMissing('cart_items', [
            'cartitems_id' => $item->cartitems_id,
        ]);
    }

    // -------------------------------------------------------
    // PLACE ORDER
    // -------------------------------------------------------

    public function test_guest_cannot_place_order()
    {
        $response = $this->post('/orders', []);
        $response->assertRedirect('/login');
    }

    public function test_customer_can_place_order()
    {
        $customer      = $this->createCustomer();
        $product       = $this->createProduct(['Stock' => 10, 'Price' => 200]);
        $paymentMethod = $this->createPaymentMethod();
        $this->actingAs($customer, 'customer');

        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => 2,
            'unitPrice'  => 200,
            'subtotal'   => 400,
        ]);

        $response = $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
        ]);

        $response->assertRedirect('/account/orders');
        $response->assertSessionHas('success', 'Order placed successfully!');

        $this->assertDatabaseHas('orders', [
            'cus_id'      => $customer->cus_id,
            'total_price' => 400,
            'order_status' => 'Pending',
        ]);
    }

    public function test_placing_order_decrements_product_stock()
    {
        $customer      = $this->createCustomer();
        $product       = $this->createProduct(['Stock' => 10, 'Price' => 200]);
        $paymentMethod = $this->createPaymentMethod();
        $this->actingAs($customer, 'customer');

        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => 3,
            'unitPrice'  => 200,
            'subtotal'   => 600,
        ]);

        $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
        ]);

        $this->assertDatabaseHas('products', [
            'product_ID' => $product->product_ID,
            'Stock'      => 7,
        ]);
    }

    public function test_placing_order_marks_cart_as_checked_out()
    {
        $customer      = $this->createCustomer();
        $product       = $this->createProduct(['Stock' => 10, 'Price' => 200]);
        $paymentMethod = $this->createPaymentMethod();
        $this->actingAs($customer, 'customer');

        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => 1,
            'unitPrice'  => 200,
            'subtotal'   => 200,
        ]);

        $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
        ]);

        $this->assertDatabaseHas('carts', [
            'cart_id' => $cart->cart_id,
            'status'  => 'checked_out',
        ]);
    }

    public function test_placing_order_with_flat_voucher_applies_discount()
    {
        $customer      = $this->createCustomer();
        $product       = $this->createProduct(['Stock' => 10, 'Price' => 500]);
        $paymentMethod = $this->createPaymentMethod();
        $voucher       = Voucher::create([
            'voucherName'   => 'FLAT100',
            'voucherType'   => 'flat',
            'voucherAmount' => 100,
        ]);
        $this->actingAs($customer, 'customer');

        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => 1,
            'unitPrice'  => 500,
            'subtotal'   => 500,
        ]);

        $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
            'voucher_id'       => $voucher->voucher_id,
        ]);

        $this->assertDatabaseHas('orders', [
            'cus_id'      => $customer->cus_id,
            'total_price' => 400, // 500 - 100
        ]);
    }

    public function test_placing_order_with_percentage_voucher_applies_discount()
    {
        $customer      = $this->createCustomer();
        $product       = $this->createProduct(['Stock' => 10, 'Price' => 200]);
        $paymentMethod = $this->createPaymentMethod();
        $voucher       = Voucher::create([
            'voucherName'   => 'SAVE10',
            'voucherType'   => 'percentage',
            'voucherAmount' => 10,
        ]);
        $this->actingAs($customer, 'customer');

        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => 1,
            'unitPrice'  => 200,
            'subtotal'   => 200,
        ]);

        $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
            'voucher_id'       => $voucher->voucher_id,
        ]);

        $this->assertDatabaseHas('orders', [
            'cus_id'      => $customer->cus_id,
            'total_price' => 180, // 200 - 10%
        ]);
    }

    public function test_cannot_place_order_with_empty_cart()
    {
        $customer      = $this->createCustomer();
        $paymentMethod = $this->createPaymentMethod();
        $this->actingAs($customer, 'customer');

        $response = $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
        ]);

        $response->assertStatus(422);
    }

    public function test_placing_order_creates_sales_records()
    {
        $customer      = $this->createCustomer();
        $product       = $this->createProduct(['Stock' => 10, 'Price' => 300]);
        $paymentMethod = $this->createPaymentMethod();
        $this->actingAs($customer, 'customer');

        $cart = Cart::create([
            'createdDate' => now()->toDateString(),
            'status'      => 'active',
            'cus_id'      => $customer->cus_id,
        ]);

        CartItem::create([
            'cart_id'    => $cart->cart_id,
            'product_ID' => $product->product_ID,
            'quantity'   => 2,
            'unitPrice'  => 300,
            'subtotal'   => 600,
        ]);

        $this->post('/orders', [
            'paymentMethod_id' => $paymentMethod->paymentMethod_id,
        ]);

        $this->assertDatabaseHas('sales', [
            'product_id'  => $product->product_ID,
            'quantity'    => 2,
            'total_price' => 600,
        ]);
    }
}