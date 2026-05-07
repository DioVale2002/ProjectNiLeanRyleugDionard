<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CatalogTest extends TestCase
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

    // -------------------------------------------------------
    // CATALOG BROWSING
    // -------------------------------------------------------

    public function test_guest_can_view_catalog()
    {
        $response = $this->get('/catalog');
        $response->assertStatus(200);
        $response->assertViewIs('catalog.index');
    }

    public function test_catalog_shows_products_with_stock()
    {
        $inStock  = $this->createProduct(['Title' => 'In Stock Book', 'Stock' => 5]);
        $outStock = $this->createProduct(['Title' => 'Out of Stock Book', 'Stock' => 0]);

        $response = $this->get('/catalog');
        $response->assertSee('In Stock Book');
        $response->assertDontSee('Out of Stock Book');
    }

    public function test_catalog_can_search_by_title()
    {
        $this->createProduct(['Title' => 'The Hobbit']);
        $this->createProduct(['Title' => 'Harry Potter']);

        $response = $this->get('/catalog?search=Hobbit');
        $response->assertSee('The Hobbit');
        $response->assertDontSee('Harry Potter');
    }

    public function test_catalog_can_search_by_author()
    {
        $this->createProduct(['Title' => 'Book A', 'Author' => 'Tolkien']);
        $this->createProduct(['Title' => 'Book B', 'Author' => 'Rowling']);

        $response = $this->get('/catalog?search=Tolkien');
        $response->assertSee('Book A');
        $response->assertDontSee('Book B');
    }

    public function test_catalog_can_filter_by_genre()
    {
        $this->createProduct(['Title' => 'Fantasy Book', 'Genre' => 'Fantasy']);
        $this->createProduct(['Title' => 'Horror Book', 'Genre' => 'Horror']);

        $response = $this->get('/catalog?genre[]=Fantasy');
        $response->assertSee('Fantasy Book');
        $response->assertDontSee('Horror Book');
    }

    public function test_catalog_filters_by_publication_date_range()
    {
        $this->createProduct([
            'Title' => 'Old Release',
            'Publication_Date' => '2020-01-01',
        ]);
        $this->createProduct([
            'Title' => 'New Release',
            'Publication_Date' => '2024-01-01',
        ]);

        $response = $this->get('/catalog?min_date=2023-01-01');

        $response->assertSee('New Release');
        $response->assertDontSee('Old Release');
    }

    public function test_catalog_can_filter_by_rating()
    {
        $this->createProduct(['Title' => 'Five Star', 'Rating' => 5]);
        $this->createProduct(['Title' => 'Three Star', 'Rating' => 3]);

        $response = $this->get('/catalog?rating=5');

        $response->assertSee('Five Star');
        $response->assertDontSee('Three Star');
    }

    public function test_catalog_can_filter_by_price_range()
    {
        $this->createProduct(['Title' => 'Budget Book', 'Price' => 100]);
        $this->createProduct(['Title' => 'Premium Book', 'Price' => 500]);

        $response = $this->get('/catalog?min_price=200');

        $response->assertSee('Premium Book');
        $response->assertDontSee('Budget Book');
    }

    public function test_catalog_can_filter_by_age_group()
    {
        $this->createProduct(['Title' => 'Kids Pick', 'Age_Group' => 'Kids']);
        $this->createProduct(['Title' => 'Adult Pick', 'Age_Group' => 'Adults']);

        $response = $this->get('/catalog?agegroup[]=Kids');

        $response->assertSee('Kids Pick');
        $response->assertDontSee('Adult Pick');
    }

    public function test_guest_can_view_product_detail()
    {
        $product = $this->createProduct();

        $response = $this->get("/catalog/{$product->product_ID}");
        $response->assertStatus(200);
        $response->assertViewIs('catalog.show');
        $response->assertSee($product->Title);
        $response->assertSee($product->Author);
    }

    public function test_product_detail_shows_out_of_stock_when_no_stock()
    {
        $product = $this->createProduct(['Stock' => 0]);

        $response = $this->get("/catalog/{$product->product_ID}");
        $response->assertSee('Out of Stock');
    }

    public function test_guest_sees_login_to_buy_instead_of_add_to_cart()
    {
        $product = $this->createProduct();

        $response = $this->get("/catalog/{$product->product_ID}");
        $response->assertSee('Log in to Buy');
    }

    public function test_authenticated_customer_sees_add_to_cart_on_detail()
    {
        $customer = $this->createCustomer();
        $product  = $this->createProduct();

        $this->actingAs($customer, 'customer');

        $response = $this->get("/catalog/{$product->product_ID}");
        $response->assertSee('Add to Cart');
    }
}