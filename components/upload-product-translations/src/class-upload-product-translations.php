<?php

namespace BytePerfect\WordPress\Theme\Tehnokrat\Components\Upload_Product_Translations;

use PhpOffice\PhpSpreadsheet\IOFactory;
use BytePerfect\EDI\Utils;
use Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;
use SitePress;
use Webmozart\Assert\Assert;

class Upload_Product_Translations {
	/**
	 * @throws Exception Exception.
	 */
	public function __construct() {
		if ( ! class_exists( '\\BytePerfect\\EDI\\Utils' ) ) {
			throw new Exception( __( 'Plugin "E-Commerce Data Interchange" is not active.', 'tehnokrat' ) );
		}
	}

	/**
	 * Process product translations upload.
	 *
	 * @param string $filename Filename.
	 *
	 * @return void
	 * @throws Exception
	 */
	public function process( string $filename ): void {
		Assert::file( $filename );

		$spreadsheet = IOFactory::load( $filename );

		$worksheet = $spreadsheet->getActiveSheet();

		$processed_product_count = 0;
		foreach ( $worksheet->getRowIterator() as $i => $row ) {
			extract( $this->get_product_translation( $row ) );

			if ( empty( $product_guid ) ) {
				wp_log_warning( sprintf( __( 'Line %d. GUID field is empty.', 'tehnokrat' ), $i ) );

				continue;
			}

			if ( empty( $name ) ) {
				wp_log_warning( sprintf( __( 'Line %d. Product name field is empty.', 'tehnokrat' ), $i ) );

				continue;
			}

			if ( empty( $description ) ) {
				wp_log_warning( sprintf( __( 'Line %d. Description field is empty.', 'tehnokrat' ), $i ) );

				continue;
			}

			if ( empty( $options ) ) {
				wp_log_warning( sprintf( __( 'Line %d. Options field is empty.', 'tehnokrat' ), $i ) );

				continue;
			}

			$product_id = Utils::get_product_id( $product_guid );
			if ( is_null( $product_id ) ) {
				wp_log_warning(
					sprintf( __( 'Line %d was skipped. Product with GUID %s does not exist.', 'tehnokrat' ), $i, $product_guid )
				);

				continue;
			}

			wp_log_warning(
				sprintf( __( 'Product with GUID %s was mapped to ID %d.', 'tehnokrat' ), $product_guid, $product_id )
			);

			wp_log_debug( __( 'Updating translation...', 'tehnokrat' ) );

			$return = update_field( 'title', $name, $product_id );
			$return = update_field( 'content', $description, $product_id );
			$return = update_field( 'excerpt', $options, $product_id );

			wp_log_debug( __( 'Done.', 'tehnokrat' ) );

			$processed_product_count ++;
		}

		wp_log_debug(
			sprintf(
				__( 'Translations have been uploaded for %d products.', 'tehnokrat' ),
				$processed_product_count
			)
		);
	}

	/**
	 * Get product translation from worksheet row.
	 *
	 * @param Row $row Row.
	 *
	 * @return array
	 */
	protected function get_product_translation( Row $row ): array {
		$product_guid = '';
		$name         = '';
		$description  = '';
		$options      = '';

		foreach ( $row->getCellIterator() as $i => $cell ) {
			if ( 'I' === $i ) {
				$product_guid = $cell->getValue();
			} elseif ( 'J' === $i ) {
				$name = $cell->getValue();
			} elseif ( 'K' === $i ) {
				$description = $cell->getValue();
			} elseif ( 'L' === $i ) {
				$options = $cell->getValue();
			}
		}

		return compact( 'product_guid', 'name', 'description', 'options' );
	}
}
