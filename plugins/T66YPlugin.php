<?php

use Proxy\Plugin\AbstractPlugin;
use Proxy\Event\ProxyEvent;

class T66YPlugin extends AbstractPlugin {

    public function process_img($matches)
    {
        if (strpos($matches[0], ' src=') > 0) {
/*             $count = preg_match('/<img (.*?)src=[\\\'\"](.+?)[\\\'\"](.*?)>/i', $matches[0], $matches2);
            if ($count == 0) {
                return $matches[0];
            } else {
                return '<img ' . $matches2[1] . 'src="' . proxify_url($matches2[2]) . '"' . $matches2[3] . '>';

            } */
            return $matches[0];
        } else {
            return '<img ' . $matches[1] . 'ess-data="' . $matches[2] . '"' . $matches[3] . ' src="' . proxify_url($matches[2]) . '" style="cursor: pointer;">';
        }
    }

	public function onCompleted(ProxyEvent $event){
	
		$request = $event['request'];
		$response = $event['response'];
		
		$url = $request->getUri();
		
		// we attach url_form only if this is a html response
		if(!is_html($response->headers->get('content-type'))){
			return;
		}

        $urlmath = preg_match('/^https?:\/\/(?:www\.)?t66y.com\/htm_data\/.+\.html$/i', $url);

        if($urlmath == false or $urlmath == 0){
            return;
        }
		
		$output = $response->getContent();
		
		$output = preg_replace_callback('/<img (.*?)ess-data=[\\\'\"](.+?)[\\\'\"](.*?)>/i', array($this, 'process_img'), $output);
		
		$response->setContent($output);
	}
}

?>