<?php

namespace Yoast\WP\SEO\Tests\Values\SEMrush;

use League\OAuth2\Client\Token\AccessTokenInterface;
use Mockery;
use Yoast\WP\SEO\Tests\TestCase;
use Yoast\WP\SEO\Values\SEMrush\SEMrush_Token;

/**
 * Class SEMrush_Token_Test
 *
 * @coversDefaultClass \Yoast\WP\SEO\Values\SEMrush\SEMrush_Token
 *
 * @group values
 * @group semrush
 */
class SEMrush_Token_Test extends TestCase {

	/**
	 * @var int
	 */
	private $created_at;

	public function setUp() {
		parent::setUp();

		$this->created_at = time();
	}

	/**
	 * Test creating a valid new instance.
	 *
	 * @covers ::__construct
	 */
	public function test_creating_new_instance() {
		$instance = new SEMrush_Token( '000000', '000001', 604800, false, $this->created_at );

		$this->assertInstanceOf( SEMrush_Token::class, $instance );
	}

	/**
	 * Test creating a new instance with an empty property.
	 *
	 * @covers ::__construct
	 * @expectedException Yoast\WP\SEO\Exceptions\SEMrush\SEMrush_Empty_Token_Property_Exception
	 */
	public function test_creating_new_instance_empty_property() {
		$instance = new SEMrush_Token( '', '000001', 604800, true, $this->created_at );
	}

	/**
	 * Test creating a new instance with an expired token.
	 *
	 * @covers ::get_access_token
	 * @covers ::get_refresh_token
	 * @covers ::get_expires
	 * @covers ::has_expired
	 */
	public function test_getters() {
		$instance = new SEMrush_Token( '000000', '000001', $this->created_at + 604800, false, $this->created_at );

		$this->assertEquals( '000000', $instance->get_access_token() );
		$this->assertEquals( '000001', $instance->get_refresh_token() );
		$this->assertEquals( $this->created_at + 604800, $instance->get_expires() );
		$this->assertFalse( $instance->has_expired() );
		$this->assertEquals( $this->created_at, $instance->get_created_at() );
	}

	/**
	 * Test converting an instance to an array.
	 *
	 * @covers ::to_array
	 */
	public function test_to_array() {
		$instance = new SEMrush_Token( '000000', '000001', $this->created_at + 604800, false, $this->created_at );

		$this->assertEquals( [
			'access_token'  => '000000',
			'refresh_token' => '000001',
			'expires'       => $this->created_at + 604800,
			'has_expired'   => false,
			'created_at'	=> $this->created_at,
		], $instance->to_array() );

	}

	/**
	 * Test creating from a response object.
	 *
	 * @covers ::from_response
	 * @covers ::__construct
	 */
	public function test_from_response() {
		$response = Mockery::mock( AccessTokenInterface::class );
		$response->allows( [
			'getToken'        => '000000',
			'getRefreshToken' => '000001',
			'getExpires'      => 604800,
			'hasExpired'      => false,
		] );

		$instance = SEMrush_Token::from_response( $response );

		$this->assertInstanceOf( SEMrush_Token::class, $instance );
		$this->assertAttributeEquals( '000000', 'access_token', $instance );
		$this->assertAttributeEquals( '000001', 'refresh_token', $instance );
		$this->assertAttributeEquals( 604800 , 'expires', $instance );
		$this->assertAttributeEquals( false, 'has_expired', $instance );
		$this->assertAttributeEquals( $this->created_at, 'created_at', $instance );
	}
}
