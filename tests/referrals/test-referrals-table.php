<?php
namespace AffWP\Referral\Admin\Table;

use AffWP\Tests\UnitTestCase;

/**
 * Referrals List Table tests.
 *
 * @covers \AffWP_Referrals_Table
 * @group admin
 * @group referralsphpu
 */
class Tests extends UnitTestCase {

	/**
	 * Affiliate ID test fixture.
	 *
	 * @access protected
	 * @var    int
	 * @static
	 */
	protected static $affiliate_id;

	/**
	 * Referrals test fixtures.
	 *
	 * @access protected
	 * @var    array<AffWP\Referral>
	 * @static
	 */
	protected static $referrals = array();

	/**
	 * Base search arguments fixture.
	 *
	 * @access protected
	 * @var    array
	 * @static
	 */
	protected static $base_search_args = array(
		'referral_id'  => 20,
		'reference'    => 'foo',
		'context'      => 'bar',
		'affiliate_id' => 10,
		'campaign'     => 'baz'
	);

	/**
	 * AffWP_Referrals_Table fixutre.
	 *
	 * @access public
	 * @var    \AffWP_Referrals_Table
	 */
	public $list_table;

	/**
	 * Set up fixtures once.
	 */
	public static function wpSetUpBeforeClass() {
		self::$affiliate_id = parent::affwp()->affiliate->create();

		self::$referrals = parent::affwp()->referral->create_many( 4, array(
			'affiliate_id' => self::$affiliate_id
		) );
	}

	/**
	 * Set up before each test.
	 */
	public function setUp() {
		parent::setUp();

		$GLOBALS['hook_suffix'] = 'affiliates_page_affiliate-wp-referrals';

		if ( ! class_exists( 'AffWP_Referrals_Table' ) ) {
			require_once AFFILIATEWP_PLUGIN_DIR . 'includes/abstracts/class-affwp-list-table.php';
			require_once AFFILIATEWP_PLUGIN_DIR . 'includes/admin/referrals/class-list-table.php';
		}

		$this->list_table = new \AffWP_Referrals_Table;
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_invalid_search_should_return_original_arguments() {
		$result = $this->list_table->parse_search( 'foobar', self::$base_search_args );

		$this->assertEqualSets( $result, self::$base_search_args );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_with_bare_referral_id_should_set_that_referral_id_argument() {
		$search = self::$referrals[0];
		$result = $this->list_table->parse_search( $search, self::$base_search_args );

		$this->assertSame( $result['referral_id'], $search );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_with_bare_referral_id_should_set_is_search_to_false() {
		$this->list_table->parse_search( self::$referrals[0], self::$base_search_args );

		$this->assertFalse( $this->list_table->is_search );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_prefixed_ref_should_set_reference() {
		$search = 'ref:foo';
		$result = $this->list_table->parse_search( $search, self::$base_search_args );

		$this->assertSame( $result['reference'], 'foo' );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_prefixed_ref_should_set_is_search_false() {
		$result = $this->list_table->parse_search( 'ref:foo', self::$base_search_args );

		$this->assertFalse( $this->list_table->is_search );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_prefixed_context_should_set_context() {
		$search = 'context:foo';
		$result = $this->list_table->parse_search( $search, self::$base_search_args );

		$this->assertSame( $result['context'], 'foo' );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_prefixed_context_should_set_is_search_false() {
		$result = $this->list_table->parse_search( 'context:foo', self::$base_search_args );

		$this->assertFalse( $this->list_table->is_search );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_prefixed_affiliate_should_set_affiliate_id() {
		$search = 'affiliate:1';
		$result = $this->list_table->parse_search( $search, self::$base_search_args );

		$this->assertSame( $result['affiliate_id'], 1 );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_prefixed_affiliate_should_set_is_search_false() {
		$result = $this->list_table->parse_search( 'affiliate:1', self::$base_search_args );

		$this->assertFalse( $this->list_table->is_search );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_prefixed_campaign_should_set_campaign() {
		$search = 'campaign:foo';
		$result = $this->list_table->parse_search( $search, self::$base_search_args );

		$this->assertSame( $result['campaign'], 'foo' );
	}

	/**
	 * @covers \AffWP_Referrals_Table::parse_search()
	 */
	public function test_parse_search_prefixed_campaign_should_set_is_search_false() {
		$result = $this->list_table->parse_search( 'campaign:foo', self::$base_search_args );

		$this->assertFalse( $this->list_table->is_search );
	}

}
