<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Voucher;
use App\Models\StockIn;
use App\Models\StockOut;
use App\Models\Archive;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    use RefreshDatabase;

    protected function createProduct(array $overrides = [])
    {
        return Product::create(array_merge([
            'Title'     => 'Test Book',
            'Author'    => 'Test Author',
            'Price'     => 199.00,
            'Stock'     => 10,
            'ISBN'      => '978-0000000001',
            'Publisher' => 'Test Publisher',
            'Genre'     => 'Fiction',
        ], $overrides));
    }

    protected function createVoucher(array $overrides = [])
    {
        return Voucher::create(array_merge([
            'voucherName'   => 'TESTCODE',
            'voucherType'   => 'flat',
            'voucherAmount' => 50,
        ], $overrides));
    }

    // -------------------------------------------------------
    // PRODUCTS
    // -------------------------------------------------------

    public function test_can_view_products_index()
    {
        $response = $this->get('/admin/products');
        $response->assertStatus(200);
        $response->assertViewIs('admin.products.index');
    }

    public function test_can_view_create_product_page()
    {
        $response = $this->get('/admin/products/create');
        $response->assertStatus(200);
        $response->assertViewIs('admin.products.create');
    }

    public function test_can_create_product_with_valid_data()
    {
        $response = $this->post('/admin/products', [
            'Title'     => 'New Book',
            'Author'    => 'Jane Doe',
            'Price'     => 299.00,
            'Stock'     => 20,
            'ISBN'      => '978-1234567890',
            'Publisher' => 'Acme Press',
            'Genre'     => 'Non-Fiction',
        ]);

        $response->assertRedirect('/admin/products');
        $response->assertSessionHas('success', 'Product added successfully.');
        $this->assertDatabaseHas('products', [
            'Title' => 'New Book',
            'ISBN'  => '978-1234567890',
        ]);
    }

    public function test_cannot_create_product_with_missing_required_fields()
    {
        $response = $this->post('/admin/products', [
            'Title' => '',
            'Author' => '',
        ]);

        $response->assertSessionHasErrors(['Title', 'Author', 'Price', 'Stock', 'ISBN', 'Publisher', 'Genre']);
    }

    public function test_cannot_create_product_with_duplicate_isbn()
    {
        $this->createProduct(['ISBN' => '978-0000000001']);

        $response = $this->post('/admin/products', [
            'Title'     => 'Another Book',
            'Author'    => 'Someone',
            'Price'     => 100,
            'Stock'     => 5,
            'ISBN'      => '978-0000000001',
            'Publisher' => 'Pub',
            'Genre'     => 'Drama',
        ]);

        $response->assertSessionHasErrors('ISBN');
    }

    public function test_can_view_product_detail()
    {
        $product = $this->createProduct();

        $response = $this->get("/admin/products/{$product->product_ID}");
        $response->assertStatus(200);
        $response->assertViewIs('admin.products.show');
        $response->assertSee('Test Book');
    }

    public function test_can_view_edit_product_page()
    {
        $product = $this->createProduct();

        $response = $this->get("/admin/products/{$product->product_ID}/edit");
        $response->assertStatus(200);
        $response->assertViewIs('admin.products.edit');
    }

    public function test_can_update_product_with_valid_data()
    {
        $product = $this->createProduct();

        $response = $this->put("/admin/products/{$product->product_ID}", [
            'Title'     => 'Updated Title',
            'Author'    => 'Updated Author',
            'Price'     => 399.00,
            'Stock'     => 5,
            'ISBN'      => '978-0000000001',
            'Publisher' => 'Test Publisher',
            'Genre'     => 'Fiction',
        ]);

        $response->assertRedirect('/admin/products');
        $response->assertSessionHas('success', 'Product updated successfully.');
        $this->assertDatabaseHas('products', [
            'product_ID' => $product->product_ID,
            'Title'      => 'Updated Title',
        ]);
    }

    public function test_cannot_update_product_isbn_to_another_products_isbn()
    {
        $product1 = $this->createProduct(['ISBN' => '978-0000000001']);
        $product2 = $this->createProduct(['ISBN' => '978-0000000002']);

        $response = $this->put("/admin/products/{$product2->product_ID}", [
            'Title'     => 'Product 2',
            'Author'    => 'Author',
            'Price'     => 100,
            'Stock'     => 5,
            'ISBN'      => '978-0000000001', // taken by product1
            'Publisher' => 'Pub',
            'Genre'     => 'Fiction',
        ]);

        $response->assertSessionHasErrors('ISBN');
    }

    public function test_archiving_product_creates_archive_record()
    {
        $product = $this->createProduct();

        $response = $this->delete("/admin/products/{$product->product_ID}");

        $response->assertRedirect('/admin/products');
        $response->assertSessionHas('success', 'Product archived successfully.');
        $this->assertDatabaseHas('archive', [
            'archivedProduct' => $product->product_ID,
        ]);
    }

    public function test_archiving_product_sets_stock_to_zero()
    {
        $product = $this->createProduct(['Stock' => 15]);

        $this->delete("/admin/products/{$product->product_ID}");

        $this->assertDatabaseHas('products', [
            'product_ID' => $product->product_ID,
            'Stock'      => 0,
        ]);
    }

    // -------------------------------------------------------
    // VOUCHERS
    // -------------------------------------------------------

    public function test_can_view_vouchers_index()
    {
        $response = $this->get('/admin/vouchers');
        $response->assertStatus(200);
        $response->assertViewIs('admin.vouchers.index');
    }

    public function test_can_create_voucher_with_valid_data()
    {
        $response = $this->post('/admin/vouchers', [
            'voucherName'   => 'SAVE20',
            'voucherType'   => 'percentage',
            'voucherAmount' => 20,
        ]);

        $response->assertRedirect('/admin/vouchers');
        $response->assertSessionHas('success', 'Voucher created successfully.');
        $this->assertDatabaseHas('vouchers', [
            'voucherName' => 'SAVE20',
            'voucherType' => 'percentage',
        ]);
    }

    public function test_cannot_create_voucher_with_invalid_type()
    {
        $response = $this->post('/admin/vouchers', [
            'voucherName'   => 'BADTYPE',
            'voucherType'   => 'freebie', // not in: percentage, flat
            'voucherAmount' => 10,
        ]);

        $response->assertSessionHasErrors('voucherType');
    }

    public function test_cannot_create_voucher_with_missing_fields()
    {
        $response = $this->post('/admin/vouchers', []);
        $response->assertSessionHasErrors(['voucherName', 'voucherType', 'voucherAmount']);
    }

    public function test_can_update_voucher()
    {
        $voucher = $this->createVoucher();

        $response = $this->put("/admin/vouchers/{$voucher->voucher_id}", [
            'voucherName'   => 'UPDATED',
            'voucherType'   => 'percentage',
            'voucherAmount' => 15,
        ]);

        $response->assertRedirect('/admin/vouchers');
        $response->assertSessionHas('success', 'Voucher updated successfully.');
        $this->assertDatabaseHas('vouchers', [
            'voucher_id'    => $voucher->voucher_id,
            'voucherName'   => 'UPDATED',
            'voucherAmount' => 15,
        ]);
    }

    public function test_can_delete_voucher()
    {
        $voucher = $this->createVoucher();

        $response = $this->delete("/admin/vouchers/{$voucher->voucher_id}");

        $response->assertRedirect('/admin/vouchers');
        $response->assertSessionHas('success', 'Voucher deleted successfully.');
        $this->assertDatabaseMissing('vouchers', [
            'voucher_id' => $voucher->voucher_id,
        ]);
    }

    // -------------------------------------------------------
    // STOCK
    // -------------------------------------------------------

    public function test_can_view_stock_page()
    {
        $response = $this->get('/admin/stock');
        $response->assertStatus(200);
        $response->assertViewIs('admin.stock.index');
    }

    public function test_stock_in_increases_product_stock()
    {
        $product = $this->createProduct(['Stock' => 10]);

        $response = $this->post('/admin/stock/in', [
            'productIn'    => $product->product_ID,
            'stockIn_date' => now()->toDateString(),
            'quantity'     => 5,
        ]);

        $response->assertRedirect('/admin/stock');
        $response->assertSessionHas('success', 'Stock increased successfully.');
        $this->assertDatabaseHas('products', [
            'product_ID' => $product->product_ID,
            'Stock'      => 15,
        ]);
        $this->assertDatabaseHas('stock_in', [
            'productIn' => $product->product_ID,
        ]);
    }

    public function test_stock_out_decreases_product_stock()
    {
        $product = $this->createProduct(['Stock' => 10]);

        $response = $this->post('/admin/stock/out', [
            'productOut'    => $product->product_ID,
            'stockOut_date' => now()->toDateString(),
            'quantity'      => 4,
        ]);

        $response->assertRedirect('/admin/stock');
        $response->assertSessionHas('success', 'Stock decreased successfully.');
        $this->assertDatabaseHas('products', [
            'product_ID' => $product->product_ID,
            'Stock'      => 6,
        ]);
        $this->assertDatabaseHas('stock_out', [
            'productOut' => $product->product_ID,
        ]);
    }

    public function test_stock_out_fails_when_quantity_exceeds_stock()
    {
        $product = $this->createProduct(['Stock' => 3]);

        $response = $this->post('/admin/stock/out', [
            'productOut'    => $product->product_ID,
            'stockOut_date' => now()->toDateString(),
            'quantity'      => 10, // more than available
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseHas('products', [
            'product_ID' => $product->product_ID,
            'Stock'      => 3, // unchanged
        ]);
    }

    public function test_stock_in_requires_valid_product()
    {
        $response = $this->post('/admin/stock/in', [
            'productIn'    => 9999, // doesn't exist
            'stockIn_date' => now()->toDateString(),
            'quantity'     => 5,
        ]);

        $response->assertSessionHasErrors('productIn');
    }

    public function test_stock_in_requires_quantity_of_at_least_one()
    {
        $product = $this->createProduct();

        $response = $this->post('/admin/stock/in', [
            'productIn'    => $product->product_ID,
            'stockIn_date' => now()->toDateString(),
            'quantity'     => 0,
        ]);

        $response->assertSessionHasErrors('quantity');
    }
}