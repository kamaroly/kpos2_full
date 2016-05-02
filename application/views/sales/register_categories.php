<?php
$this->load->helper('captcha');
foreach ($categories->result() as $category):
$image_properties = array(
          'src' => 'images/items/categories/category.png',
          'alt' => $category->description,
          'class' => 'post_images',
          'width' => '100',
          'height' => '100',
          'title' => $category->cname,
          'rel' => 'lightbox',
);

echo anchor('sales/items_by_category/'.$category->id,img($image_properties));

endforeach;

