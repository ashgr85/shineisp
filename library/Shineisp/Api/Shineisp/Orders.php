<?
class Shineisp_Api_Shineisp_Orders extends Shineisp_Api_Shineisp_Abstract_Action  {
    
    public function create( $params ) {
        $this->authenticate();

        $uuid       = $params['uuid'];
        $customers  = Customers::find($uuid); 
        if( empty($customers) ) {
            throw new Shineisp_Api_Shineisp_Exceptions( 400006, ":: 'uuid' not valid" );
            exit();
        }
        
        $trancheid  = intval($params['trancheid']);
        $tranche    = ProductsTranches::getTranchebyId($trancheid);
        if( empty($tranche) ) {
            throw new Shineisp_Api_Shineisp_Exceptions( 400006, ":: 'trancheid' not valid" );
            exit();
        }
        
        #Check Products
        if( empty( $params['products']) && ! is_array($params['products']) ) {
            throw new Shineisp_Api_Shineisp_Exceptions( 400006, ":: not 'products' choose" );
            exit();            
        }
        
        foreach( $params['products'] as $product ) {
            $productid  = intval( $product['productid']);
            $billingid  = intval( $product['billingid']);
            $ttry       = ProductsTranches::getTranchesBy_ProductId_BillingId($productid, $billingid);
            if( empty( $ttry) ) {
                throw new Shineisp_Api_Shineisp_Exceptions( 400006, ":: 'productid' or 'bilingid' not valid" );
                exit();            
            }
            
            $ttry   = array_shift($ttry);
            if( $ttry['tranche_id'] != $trancheid ) {
                throw new Shineisp_Api_Shineisp_Exceptions( 400006, ":: 'bilingid' not valid" );
                exit();            
            }
        }
        $id         = $customers['customer_id'];
        $isVATFree  = Customers::isVATFree($id);
        
        if( $params['status'] == "complete" ) {
            $status = Statuses::id('complete', 'orders');   
        } else {
            $status = Statuses::id('tobepaid', 'orders');
        }
        
        $theOrder = Orders::create ( $customers['customer_id'], $status, $params ['note'] );
        
        foreach( $params['products'] as $product ) {
            $productid  = intval( $product['productid']);
            $billingid  = intval( $product['billingid']);
            $quantity   = intval( $product['quantity']);
            $p          = Products::getAllInfo($productid);
            
            Orders::addItem ( $productid, $quantity, $billingid, $trancheid, $p['ProductsData'][0]['name'], array() );   
        }

        $orderID = $theOrder ['order_id'];
        if( $params['sendemail'] == 1 ) {
            Orders::sendOrder ( $orderID );
        }     
        
        $banks = Banks::find ( $params['payment'], "*", true );
        if (! empty ( $banks [0] ['classname'] )) {
            
            $class = $banks [0] ['classname'];
            if (class_exists ( $class )) {
                // Get the payment form object
                $banks = Banks::findbyClassname ( $class );
                $gateway = new $class ( $orderID );
                $gateway->setFormHidden ( true );
                $gateway->setRedirect ( true );
                
                $gateway->setUrlOk ( $_SERVER ['HTTP_HOST'] . "/orders/response/gateway/" . md5 ( $banks ['classname'] ) );
                $gateway->setUrlKo ( $_SERVER ['HTTP_HOST'] . "/orders/response/gateway/" . md5 ( $banks ['classname'] ) );
                $gateway->setUrlCallback ( $_SERVER ['HTTP_HOST'] . "/common/callback/gateway/" . md5 ( $banks ['classname'] ) );
                
                return $gateway->CreateForm ();
            }
        } 
            
        
        throw new Shineisp_Api_Shineisp_Exceptions( 400006, ":: bad request" );
        exit();            
        
                
    }
    
}