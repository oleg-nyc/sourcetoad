<?php
function GetShippingRate($address){
	if( isset($address) ){
		return rand(1,99);
	}
	else return 0;
}
class Customer {
	protected $def = array (
								'first_name'  => 'name',
								'last_name'   => 'name',
								'address' => array (
								array (
										'address_1',
										'address_2',
										'city',
										'state',
										'zip',
									),
								),
							);
	protected $html = [];
	protected $address = "";
	protected $customer_structure = ['first_name', 'last_name', 'address' ];
	
	public function __construct($data = false){
		if( !isset($_SESSION['customer']) || !is_array($_SESSION['customer']) || count($_SESSION['customer']) < 1 )
			$_SESSION['customer'] = $this->def;
		foreach($this->customer_structure as $k){
			if( $data ) $_SESSION['customer'][$k] = $data[$k];
			if( is_array($_SESSION['customer'][$k]) ){
				foreach( $this->recurcive($_SESSION['customer'][$k], $k) as $dept => $value ){
					$this->html[$dept] = $value;
					if( $k = "address" ) $this->address .= $value . ' ';
				}
			}
			else
			$this->html[$k] = $_SESSION['customer'][$k];
		}
	}
	protected function Shipping(){
		return GetShippingRate($this->address);
	}
	protected function recurcive($arr, $dept='', &$result=[]){
		foreach( $arr as $k => $v ){
			if( !is_array($v) ) $result[$dept . '['. $k .']'] = $v;
			else $this->recurcive($v, $dept . '['. $k .']', $result);
		}
		return $result;
	}
	public function printHTML($key=''){
		if( !is_array($this->html) ){
			echo "wrong format";
			return false;
		} 
		foreach( $this->html as $k => $v ){
			$name = isset($key) && strlen($key) > 0 ? $key : $k;
			if( !is_array($v) ) {
				//$name = isset($this->label[$k]) ? $this->label[$k] : '';
                     echo "<input name='". $name ."' value='" . $v  . "'>";
			}
			else 
				$this->printHTML($v, $name);
		}
	}
}

class Cart extends Customer {
	protected $required_feilds = ['name' => 'text', 'quantity' => 'number'];
	protected $structure = ['id', 'name', 'price', 'quantity' ];
	protected $tax_rate = 7; //
	protected $items = [];
	
	public function __construct($data = false, $customer_data = false){
		parent::__construct($customer_data);
		if( $data ) {
			$temp = [];
			foreach( $this->required_feilds as $k => $type)
			{
				$temp[$k] = $data[$k];
			}
			$temp['price'] = rand(1, 999);
			$_SESSION['cart'][] = $temp;
			$id = array_key_last($_SESSION['cart']);
			$_SESSION['cart'][$id]['id'] = $id;
		}
		$this->items = isset($_SESSION['cart']) && count($_SESSION['cart']) > 0 ? $_SESSION['cart'] : [] ;
	}
	
	public function Form(){
		foreach( $this->required_feilds as $name => $type){
			echo "<input type='". $type ."' name='". $name ."' placeholder='". $name ."' required>";
		}
	}
	
	public function deleteItem($id){
		unset($this->items[$id]);
		$_SESSION['cart'] = $this->items;
	}
	
	public function printCart(){
		$colspan = count($this->structure);
		if( !isset($this->items) || !is_array($this->items) || count($this->items) < 1 ) { 
			echo "<tr><td align='center' colspan='". ($colspan + 1) ."'>Your Cart is empty.</td></tr>";
			return false;
		}
		$row = "";
		$sub_total = 0;
		foreach( $this->items as $id => $item )
		{
			$row .= "<tr>";
			foreach( $this->structure as $n )
			{
				$row .= "<td align='center'>". $item[$n] ."</td>";
			}
			$item_total = $item['quantity'] * $item['price'];
			$sub_total += $item_total;
			$row .= "<td>$". $item_total ."</td>";
			$row .= "<td><form method='post'><button type='submit' name='id' value='". $id ."'>Remove Item</button><input type='hidden' name='_method' value='delete'></form></td>";
			$row .= "</tr>";
		}
		$tax = ($sub_total * $this->tax_rate)/100;
		$shipping_rate = $this->Shipping();
		$total = $sub_total + $tax + $shipping_rate;
		$row .= "<tr><td align='right' colspan='". $colspan ."'>Subtotal:</td><td>$". $sub_total ."</td></tr>";
		$row .= "<tr><td align='right' colspan='". $colspan ."'>Tax:</td><td>". $this->tax_rate . "% ($". $tax .")</td></tr>";
		$row .= "<tr><td align='right' colspan='". $colspan ."'>Shipping:</td><td>$". $shipping_rate ."</td></tr>";
		$row .= "<tr><td align='right' colspan='". $colspan ."'>Total:</td><td>$". $total ."</td></tr>";
		$row .= "<tr><td align='right'>Shipping Address:</td><td colspan='". $colspan ."'>". $this->address . "</td></tr>";
		echo $row;
	}
}
