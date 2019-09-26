<?php
/**
 * Presenter of the meta description for the home page.
 *
 * @package Yoast\YoastSEO\Presenters
 */

namespace Yoast\WP\Free\Presenters\Home_Page;

use WPSEO_Options;
use Yoast\WP\Free\Helpers\Current_Page_Helper;
use Yoast\WP\Free\Models\Indexable;
use Yoast\WP\Free\Presenters\Abstract_Meta_Description_Presenter;

/**
 * Class Meta_Description_Presenter
 */
class Meta_Description_Presenter extends Abstract_Meta_Description_Presenter {
	/**
	 * @var Current_Page_Helper
	 */
	private $current_page_helper;

	/**
	 * Front_End_Integration constructor.
	 *
	 * @param Current_Page_Helper $current_page_helper The current post helper.
	 */
	public function __construct(
		Current_Page_Helper $current_page_helper
	) {
		$this->current_page_helper = $current_page_helper;
	}

	/**
	 * Generates the meta description for an indexable.
	 *
	 * @param Indexable $indexable The indexable.
	 *
	 * @return string The meta description.
	 */
	public function generate( Indexable $indexable ) {
		if ( $indexable->description ) {
			return $indexable->description;
		}

		return WPSEO_Options::get( 'metadesc-home-wpseo' );
	}

	/**
	 * Gets an object to be used as a source of replacement variables.
	 *
	 * @param Indexable $indexable The indexable.
	 *
	 * @return array A key => value array of variables that may be replaced.
	 */
	protected function get_replace_vars_object( Indexable $indexable ) {
		if ( $this->current_page_helper->is_home_static_page() ) {
			return \get_post( $indexable->object_id );
		}
		return [];
	}
}
