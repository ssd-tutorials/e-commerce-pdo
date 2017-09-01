<?php

namespace SSD;

use \PDOException;


class Order extends Application {

	protected $_table = 'orders';
    protected $_table_2 = 'orders_items';
    protected $_table_3 = 'statuses';
    protected $_table_4 = 'countries';
    protected $_table_5 = 'products';
	
	private $_basket = array();
	
	private $_items = array();
	
	
	
	
	
	
	public function getItems() {
	
		$this->_basket = Session::getSession('basket');
		if (!empty($this->_basket)) {
			$objCatalogue = new Catalogue();
			foreach($this->_basket as $key => $value) {
				$this->_items[$key] = $objCatalogue->getProduct($key);
			}
		}
	
	}
	





    private function _isUserAndItemsValid($user = null) {

        return (!empty($user) && !empty($this->_items));

    }
	
	
	
	
	
	
	
	
	public function createOrder($user = null) {
	
		$this->getItems();
		
		if ($this->_isUserAndItemsValid($user)) {
		
			$objBasket = new Basket($user);
			$objBusiness = new Business();
			$business = $objBusiness->getOne(Business::BUSINESS_ID);

            $array = array(

                'vat_number' => $business['vat_number'],
                'client' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'address_1' => $user['address_1'],
                'address_2' => $user['address_2'],
                'town' => $user['town'],
                'county' => $user['county'],
                'post_code' => $user['post_code'],
                'country' => $user['country'],
                'shipping_type' => $objBasket->final_shipping_type,
                'shipping_cost' => $objBasket->final_shipping_cost,
                'vat_rate' => $objBasket->vat_rate,
                'vat' => $objBasket->final_vat,
                'subtotal_items' => $objBasket->sub_total,
                'subtotal' => $objBasket->final_sub_total,
                'total' => $objBasket->final_total,
                'date' => Helper::setDate(),
                'token' => date('YmdHis').mt_rand().md5(time())

            );
			
			
			// shipping data
			if ($user['same_address'] == 1) {

                $array['ship_address_1'] = $user['address_1'];
                $array['ship_address_2'] = $user['address_2'];
                $array['ship_town'] = $user['town'];
                $array['ship_county'] = $user['county'];
                $array['ship_post_code'] = $user['post_code'];
                $array['ship_country'] = $user['country'];
				
			} else {

                $array['ship_address_1'] = $user['ship_address_1'];
                $array['ship_address_2'] = $user['ship_address_2'];
                $array['ship_town'] = $user['ship_town'];
                $array['ship_county'] = $user['ship_county'];
                $array['ship_post_code'] = $user['ship_post_code'];
                $array['ship_country'] = $user['ship_country'];
				
			}
			
			
			try {

                $this->_Db->beginTransaction();

                $this->_Db->insertTransaction($this->_table, $array);

                $this->id = $this->_Db->id;


                foreach($this->_items as $item) {

                    $this->_Db->insertTransaction($this->_table_2, array(
                        'order' => $this->id,
                        'product' => $item['id'],
                        'price' => $item['price'],
                        'qty' => $this->_basket[$item['id']]['qty']
                    ));

                }

                $this->_Db->commit();

                return true;


            } catch (PDOException $e) {

                $this->_Db->rollBack();

                return false;

            }
			
		}
		
		return false;
	
	}

	
	
	
	
	
	
	
	
	
	
	public function getOrder($id = null) {
	
		$id = !empty($id) ? $id : $this->id;
		
		$sql = "SELECT `o`.*,
				DATE_FORMAT(`o`.`date`, '%D %M %Y %r') AS `date_formatted`,
				CONCAT_WS(' ', `o`.`first_name`, `o`.`last_name`) AS `full_name`,
				IF (
					`o`.`address_2` != '',
					CONCAT_WS(', ', `o`.`address_1`, `o`.`address_2`),
					`o`.`address_1`					
				) AS `address`,
				IF (
					`o`.`ship_address_2` != '',
					CONCAT_WS(', ', `o`.`ship_address_1`, `o`.`ship_address_2`),
					`o`.`ship_address_1`					
				) AS `ship_address`,
				(
					SELECT `name`
					FROM `{$this->_table_4}`
					WHERE `id` = `o`.`country`
				) AS `country_name`,
				(
					SELECT `name`
					FROM `{$this->_table_4}`
					WHERE `id` = `o`.`ship_country`
				) AS `ship_country_name`
				FROM `{$this->_table}` `o`
				WHERE `o`.`id` = ?";
		return $this->_Db->fetchOne($sql, $id);
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function getOrderByToken($token = null) {
	
		if (!empty($token)) {
		
			$sql = "SELECT `o`.*,
					DATE_FORMAT(`o`.`date`, '%D %M %Y %r') AS `date_formatted`,
					CONCAT_WS(' ', `o`.`first_name`, `o`.`last_name`) AS `full_name`,
					IF (
						`o`.`address_2` != '',
						CONCAT_WS(', ', `o`.`address_1`, `o`.`address_2`),
						`o`.`address_1`					
					) AS `address`,
					IF (
						`o`.`ship_address_2` != '',
						CONCAT_WS(', ', `o`.`ship_address_1`, `o`.`ship_address_2`),
						`o`.`ship_address_1`					
					) AS `ship_address`,
					(
						SELECT `name`
						FROM `{$this->_table_4}`
						WHERE `id` = `o`.`country`
					) AS `country_name`,
					(
						SELECT `name`
						FROM `{$this->_table_4}`
						WHERE `id` = `o`.`ship_country`
					) AS `ship_country_name`
					FROM `{$this->_table}` `o`
					WHERE `o`.`token` = ?";
			return $this->_Db->fetchOne($sql, $token);
			
		}

        return null;
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public function getOrderItems($id = null) {
		
		$id = !empty($id) ? $id : $this->id;
		
		$sql = "SELECT `i`.*,
				`p`.`name`,
				(`i`.`price` * `i`.`qty`) AS `price_total`
				FROM `{$this->_table_2}` `i`
				LEFT JOIN `{$this->_table_5}` `p`
					ON `p`.`id` = `i`.`product`
				WHERE `i`.`order` = ?";
		return $this->_Db->fetchAll($sql, $id);
		
	}
	
	
	
	
	private function _isValidApproval($array = null, $result = null) {

        return (
            !empty($array) &&
            !empty($result) &&
            array_key_exists('txn_id', $array) &&
            array_key_exists('payment_status', $array) &&
            array_key_exists('custom', $array)
        );

    }
	
	
	
	
	
	
	public function approve($array = null, $result = null) {

		if ($this->_isValidApproval($array, $result)) {

            $active = $array['payment_status'] == 'Completed' ? 1 : 0;

            $out = array();

            foreach($array as $key => $value) {
                $out[] = "{$key} : {$value}";
            }

            $out = implode("\n", $out);

            return $this->_Db->update($this->_table, array(
                'pp_status' => $active,
                'txn_id' => $array['txn_id'],
                'payment_status' => $array['payment_status'],
                'ipn' => $out,
                'response' => $result
            ), $array['custom'], 'token');

		}

        return false;

	}
	
	
	
	
	
	
	
	
	
	public function getClientOrders($client_id = null) {

		if (!empty($client_id)) {

			$sql = "SELECT *
			        FROM `{$this->_table}`
					WHERE `client` = ?
					ORDER BY `date` DESC";

			return $this->_Db->fetchAll($sql, $client_id);

		}

        return null;

	}
	
	
	
	
	
	
	
	
	
	public function getStatus($id = null) {

		if (!empty($id)) {

			$sql = "SELECT *
			        FROM `{$this->_table_3}`
					WHERE `id` = ?";

			return $this->_Db->fetchOne($sql, $id);

		}

        return null;

	}
	
	
	
	
	
	
	
	
	
	public function getOrders($srch = null) {

        $array = array();

		$sql = "SELECT *
                FROM `{$this->_table}`";
		if (!empty($srch)) {
            $sql .= " WHERE `id` = ?";
            $array[] = $srch;
        }

		$sql .= " ORDER BY `date` DESC";

		return $this->_Db->fetchAll($sql, $array);

	}
	
	
	
	
	
	
	
	
	public function getStatuses() {

		$sql = "SELECT *
                FROM `{$this->_table_3}`
				ORDER BY `id` ASC";
		return $this->_Db->fetchAll($sql);

	}






    private function _isUpdateOrderValid($id = null, $array = null) {

        return (
            !empty($id) &&
            !empty($array) &&
            is_array($array) &&
            array_key_exists('status', $array) &&
            array_key_exists('notes', $array)
        );

    }
	
	
	
	
	
	
	
	public function updateOrder($id = null, $array = null) {

		if ($this->_isUpdateOrderValid($id, $array)) {

			return $this->_Db->update($this->_table, array(
                'status' => $array['status'],
                'notes' => $array['notes']
            ), $id);

		}

        return false;

	}
	
	
	
	
	
	
	
	
	
	
	





}