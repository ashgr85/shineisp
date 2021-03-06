<?php

/**
 * ProductsMedia
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class ProductsMedia extends BaseProductsMedia
{
    /**
     * Add new image to a product
     * 
     * 
     * @param string $filename
     * @param string $description
     * @param integer $product_id
     */
	public static function addMedia($filename, $description, $product_id, $is_default=1){
		$media = new ProductsMedia ();
		$media->filename = $filename;
		$media->path = "/media/products/$filename";
		$media->product_id = $product_id;
		$media->is_default = $is_default;
		$media->description = $description;
		$media->enabled = 1;
		return $media->trySave ();
	}
	
    /**
     * Get all the images and media files by Productid
     * 
     * 
     * @param $productid
     * @return Doctrine Record
     */
    public static function getMediabyProductId($productid, $fields="*", $locale=1) {
        $records = array();
    	$locale = is_numeric($locale) ? $locale : 1;
    	
    	if($fields =="*"){
    		$fields = 'pm.*, p.product_id as id, p.uri as url, p.price_1 as price, pd.productdata_id as idpd, pd.name as name, pd.description as productdesc';
    	}
    	
        if(is_numeric($productid)){
        	
	    	$dq = Doctrine_Query::create ()
						    	->select($fields)
						        ->from ( 'ProductsMedia pm' )
						        ->leftJoin('pm.Products p')
						        ->leftJoin('p.ProductsData pd')
						        ->where ( "pm.product_id = ?", $productid )
						        ->orderBy('is_default asc');
	        $records =  $dq->execute ( array (), Doctrine_Core::HYDRATE_ARRAY );
	       
        }
        
        return $records;
    }   
    
    /**
     * Get the media by id
     * 
     * 
     * @param $id
     * @return Doctrine Record
     */
    public static function getMediabyId($id) {
        if(is_numeric($id)){
        	return Doctrine::getTable ( 'ProductsMedia' )->find($id, Doctrine_Core::HYDRATE_ARRAY);
        }
        return false;
    }   
    
    /**
     * Get the media by filename
     * 
     * 
     * @param string $filename
     * @return Doctrine Record
     */
    public static function getMediabyFilename($filename) {
        if(!empty($filename)){
        	return Doctrine::getTable ( 'ProductsMedia' )->findByFilename($filename, Doctrine_Core::HYDRATE_ARRAY);
        }
        return false;
    }   
    
    /**
     * delMediabyId
     * Delete the media by id
     * @param $id
     * @return Boolean
     */
    public static function delMediabyId($id) {
        if(is_numeric($id)){
        	return Doctrine::getTable ( 'ProductsMedia' )->find($id)->delete();
        }
        return false;
    }   
}