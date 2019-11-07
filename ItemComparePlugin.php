<?php

/* 
 * Item Compare Plugin
 *
 *
 */

class ItemComparePlugin extends Omeka_Plugin_AbstractPlugin 
{
  protected $_filters = array(
   		'public_navigation_admin_bar',  		
  );
    
  protected $_hooks = array(
  	'define_routes',  		
  );
    
  public function filterPublicNavigationAdminBar($nav)
  {
    	$nav[] = array(
        'label' => __('Comparer des notices'),
        'uri' => url('/compareitems')
      );      	
      return $nav;    
  }
  
  function hookDefineRoutes($args)
  {
		$router = $args['router'];
		
		$router->addRoute(
				'ic_compare_items',
				new Zend_Controller_Router_Route(
						'compareitems',
						array(
								'module' => 'item-compare',
								'controller'   => 'comparison',
								'action'       => 'compare-items',
// 								'id'					=> '',
						)
				)
		);
 		$router->addRoute(
				'ic_ajax_items_autocomplete',
				new Zend_Controller_Router_Route(
						'itemcompareajax/:title', 
						array(
								'module' => 'item-compare',
								'controller'   => 'comparison',
								'action'       => 'item-ajax',
								'title'					=> ''
						)
				)
		);	
 		$router->addRoute(
				'ic_ajax_item_fill',
				new Zend_Controller_Router_Route(
						'itemfill/:id', 
						array(
								'module' => 'item-compare',
								'controller'   => 'comparison',
								'action'       => 'item-fill-ajax',
								'id' => ''
						)
				)
		);			
  }  
}

