<?php
$api_source = ( 'pdf' === $this->args['file_type'] && 'browser' === $this->args['pdf_file_api'] ) ? '' : '//docs.google.com/viewer?embedded=true&url=';

$html  = '<div ' . FusionBuilder::attributes( 'elegant-document-viewer' ) . '>';
$html .= '<embed src="' . $api_source . $this->args['file_url'] . '" style="width: 100%; height: 100%;"></embed>';
$html .= '</div>';
