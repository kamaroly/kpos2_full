<?php
foreach ($items as $item):
  $image_properties = array(
	  	'src' => 'images/items/items/'.$item->image,
		'alt' => $item->description,
		'class' => 'post_images',
		'width' => '80',
		'height' => '80',
		'title' => $item->name,
		'rel' => 'lightbox',
);

echo anchor('sales/add/'.$item->item_id,img($image_properties));

endforeach;