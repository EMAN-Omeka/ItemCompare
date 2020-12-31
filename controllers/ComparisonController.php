<?php

class ItemCompare_ComparisonController extends Omeka_Controller_AbstractActionController
{

  public function itemAjaxAction()
	{
		$search = strtoupper($this->getParam('q'));
		$db = get_db();
		if (current_user()) {
  		$query = "SELECT record_id id, text FROM `$db->ElementTexts` WHERE record_type = 'Item' AND element_id = 50 AND UPPER(text) LIKE '%" . $search . "%'";
		} else {
  		$query = "SELECT record_id id, text FROM `$db->ElementTexts` e LEFT JOIN `$db->Items` i ON i.id = e.record_id WHERE i.public = 1 AND record_type = 'Item' AND element_id = 50 AND UPPER(text) LIKE '%" . $search . "%'";
		}
		$files = $db->query($query)->fetchAll();
		$this->_helper->json($files);
	}

  public function itemFillAjaxAction()
	{
  	$itemId = $this->getParam('id');
  	$item = $this->displayItem($itemId);
		$this->_helper->json($item);
  }

  public function compareItemsAction() {
    $this->view->left = "";
    $this->view->right = "";
  }

  public function displayItem($itemId) {
    if ($item = get_record_by_id('Item', $itemId)) {
      set_current_record('Item', $item);
    } else {
      return 'Notice privée. Connectez-vous pour y accéder.';
    }
    $content = "<div id='ci-files'>" . $this->displayFiles($item) . "</div>";
    $content .= all_element_texts('item', array('show_empty_elements' => true, 'show_element_set_headings' => true));
    $content .= "<hr /><a href='" . WEB_ROOT . "/items/show/" . $itemId . "' target='_blank'>Visualiser la notice</a>";
    if (current_user()) {
      $content .= " - <a href='" . WEB_ROOT . "/admin/items/edit/" . $itemId . "' target='_blank'>Modifier la notice</a>";
    }
    return $content;
  }

 	public function displayFiles($item) {
		$fileGallery = "";
		if (metadata('item', 'has files')) {
      ob_start();
			set_loop_records('files', $item->Files);
			foreach (loop('files') as $file):
  			if (in_array($file->getExtension(), array('jpg', 'JPG', 'jpeg', 'JPEG'))) {
  				fire_plugin_hook('book_reader_item_show', array(
  				'view' => $this,
  				'item' => $item,
  				'page' => '0',
  				'embed_functions' => false,
  				'mode_page' => 1,
  				));
  				break;
  			} elseif ($file->getExtension() == 'pdf') {
  				echo  '<iframe width=100% height=800 src="' . WEB_ROOT . '/files/original/'. metadata($file,'filename').'"></iframe>';	break;
  			} else {
  				echo files_for_item();
  				break;
  			}
			endforeach;
      $fileGallery = ob_get_contents();
      ob_end_clean();
		} else {
//   		$fileGallery = "<div id='imgwrap' style='height:486px'></div>";
		}
		return $fileGallery;
	}

  public function fileInfo($fileId) {
    $file = get_record_by_id('File', $fileId);
    $content = "<div id='primaryImage'>";
    if (isset($size) && $size[1] > 1000) {
      $content .= "<em>Passez le curseur sur l'image pour zoomer.</em>";
    }
		$filename = str_replace(array('png', 'JPG', 'jpeg'), 'jpg' , $file->filename);
		$content .= '<div class="panzoom">' . file_markup($file, array('imageSize'=>'fullsize', 'linkToFile' => false, 'imgAttributes'=>array('class'=>'zoomImage', 'data-zoom-image'=> WEB_ROOT . '/files/fullsize/' . $filename))) . '</div>';
    $content .= "</div>";

  	$filen = metadata($file, 'filename');
  	$filepath = BASE_DIR.'/files/original/'. $filen;
  	$fichier = pathinfo($filepath);
  	$ext = strtolower($fichier['extension']);
  	$fileSize =  round(filesize($filepath) / 1024 / 1024, 2);
  	$fileFormat =  mime_content_type($filepath);
  	$fileOriginal = metadata($file, 'original_filename');
  	if (in_array($ext, array('jpg', 'jpeg', 'png'))) {
  		$size = getimagesize($filepath);
  		$this->view->size = $size;
  	}
    $fileTitle = metadata($file, array('Dublin Core', 'Title')) ? strip_formatting(metadata($file, array('Dublin Core', 'Title'))) : metadata('file', 'original filename');
    if ($fileTitle != '') {
        $fileTitle = ': &quot;' . $fileTitle . '&quot; ';
    } else {
        $fileTitle = '';
    }
  	$fileTitle = __('Fichier ') . $fileTitle;
    $content .= "Nom original : <a href='" . WEB_ROOT . "/files/original/$filen'>$fileOriginal</a><br/>";
    $content .= "Extension : $fileFormat <br/>";
    $content .= "Poids : $fileSize Mo<br />";
    if (in_array($ext, array('jpg', 'jpeg', 'png'))) {
      $content .= "Dimensions : " . round($size[0]) . " x " . round($size[1]) . " px<br/>";
    }
    return $content;
  }
}