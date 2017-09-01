<?php

namespace SSD;

use \PDOException;


class Shipping extends Application {
	
	protected $_table = 'shipping';
    protected $_table_2 = 'shipping_type';
    protected $_table_3 = 'zones';
    protected $_table_4 = 'zones_post_codes';
	
	public $objBasket;
	
	
	
	
	public function __construct($objBasket = null) {
		parent::__construct();
		$this->objBasket = is_object($objBasket) ? $objBasket : new Basket();
	}
	
	
	
	
	
	
	
	public function getType($id = null) {
		if (!empty($id)) {
			$sql = "SELECT *
					FROM `{$this->_table_2}`
					WHERE `id` = ?";
			return $this->_Db->fetchOne($sql, $id);
		}
        return null;
	}
	
	
	
	
	
	
	
	public function getZones() {
		$sql = "SELECT `z`.*,
				(
					SELECT GROUP_CONCAT(`post_code` ORDER BY `post_code` ASC SEPARATOR ', ')
					FROM `{$this->_table_4}`
					WHERE `zone` = `z`.`id`
				) AS `post_codes`
				FROM `{$this->_table_3}` `z`
				ORDER BY `z`.`name` ASC";
		return $this->_Db->fetchAll($sql);
	}
	
	
	
	
	
	public function getTypes($local = 0) {
		$sql = "SELECT *
				FROM `{$this->_table_2}`
				WHERE `local` = ?
				ORDER BY `order` ASC";
		return $this->_Db->fetchAll($sql, $local);
	}
	
	
	
	
	
	
	
	
	
	private function getLastType($local = 0) {
		$sql = "SELECT `order`
				FROM `{$this->_table_2}`
				WHERE `local` = ?
				ORDER BY `order` DESC
				LIMIT 0, 1";
		return $this->_Db->fetchOne($sql, $local);
	}
	
	
	
	
	
	
	
	
	
	
	
	public function addType($params = null) {
		if (!empty($params)) {
			$params['local'] = !empty($params['local']) ? 1 : 0;
			$last = $this->getLastType($params['local']);
			$params['order'] = !empty($last) ? $last['order'] + 1 : 1;
            return $this->_Db->insert($this->_table_2, $params);
		}
		return false;
	}
	
	
	
	
	public function removeType($id = null) {

		if (empty($id)) {
            return false;
        }

        try {

            $this->_Db->beginTransaction();

            $this->_Db->deleteTransaction($this->_table_2, $id);
            $this->_Db->deleteTransaction($this->_table, $id, 'type');

            $this->_Db->commit();

            return true;


        } catch (PDOException $e) {

            $this->_Db->rollBack();

            return false;

        }

	}
	
	
	
	
	
	
	
	
	
	public function updateType($params = null, $id = null) {
		return $this->_Db->update($this->_table_2, $params, $id);
	}
	
	
	
	
	
	
	
	
	public function setTypeDefault($id = null, $local = 0) {

        if (empty($id)) {
            return false;
        }

        try {

            $local = empty($local) ? 0 : 1;

            $this->_Db->beginTransaction();

            $sql = "UPDATE `{$this->_table_2}`
                    SET `default` = ?
                    WHERE `local` = ?
                    AND `id` != ?";

            $this->_Db->executeTransaction($sql, array(
                0, $local, $id
            ));

            $sql = "UPDATE `{$this->_table_2}`
                    SET `default` = ?
                    WHERE `local` = ?
                    AND `id` = ?";

            $this->_Db->executeTransaction($sql, array(
                1, $local, $id
            ));

            $this->_Db->commit();

            return true;

        } catch (PDOException $e) {

            $this->_Db->rollBack();
            return false;

        }

	}
	
	
	
	
	
	public function duplicateType($id = null) {
		
		$type = $this->getType($id);
		
		if (empty($type)) {
            return false;
        }
			
        $last = $this->getLastType($type['local']);
        $order = !empty($last) ? $last['order'] + 1 : 1;

        try {

            $this->_Db->beginTransaction();

            $this->_Db->insertTransaction($this->_table_2, array(
                'name' => $type['name'].' copy',
                'order' => $order,
                'local' => $type['local'],
                'active' => 0
            ));

            $newId = $this->_Db->id;

            $sql = "SELECT *
                    FROM `{$this->_table}`
                    WHERE `type` = ?";
            $list = $this->_Db->fetchAll($sql, $id);

            if (!empty($list)) {

                foreach($list as $row) {

                    $this->_Db->insertTransaction($this->_table, array(
                        'type' => $newId,
                        'zone' => $row['zone'],
                        'country' => $row['country'],
                        'weight' => $row['weight'],
                        'cost' => $row['cost']
                    ));

                }

            }

            $this->_Db->commit();

            return true;

        } catch (PDOException $e) {

            $this->_Db->rollBack();
            return false;

        }
		
	}
	
	
	
	
	
	
	
	
	public function getZoneById($id = null) {
		return $this->_Db->selectOne($this->_table_3, $id);
	}





    private function _isTypeZoneEmpty($typeId = null, $zoneId = null) {

        return (!empty($typeId) && !empty($zoneId));

    }
	
	
	
	
	
	public function getShippingByTypeZone($typeId = null, $zoneId = null) {
		if ($this->_isTypeZoneEmpty($typeId, $zoneId)) {
			$sql = "SELECT `s`.*,
					IF (
						(
							SELECT COUNT(`weight`)
							FROM `{$this->_table}`
							WHERE `type` = `s`.`type`
							AND `zone` = `s`.`zone`
							AND `weight` < `s`.`weight`
							ORDER BY `weight` DESC
							LIMIT 0, 1
						) > 0,
						(
							SELECT `weight`
							FROM `{$this->_table}`
							WHERE `type` = `s`.`type`
							AND `zone` = `s`.`zone`
							AND `weight` < `s`.`weight`
							ORDER BY `weight` DESC
							LIMIT 0, 1
						) + 0.01,
						0
					) AS `weight_from`
					FROM `{$this->_table}` `s`
					WHERE `s`.`type` = ?
					AND `s`.`zone` = ?
					ORDER BY `s`.`weight` ASC";
			return $this->_Db->fetchAll($sql, array($typeId, $zoneId));
		}
        return null;
	}
	
	
	


    private function _isTypeZoneWeightNotEmpty($typeId = null, $zoneId = null, $weight = null) {

        return (!empty($typeId) && !empty($zoneId) && !empty($weight));

    }


	
	
	
	public function isDuplicateLocal($typeId = null, $zoneId = null, $weight = null) {
		if ($this->_isTypeZoneWeightNotEmpty($typeId, $zoneId, $weight)) {
			$sql = "SELECT *
					FROM `{$this->_table}`
					WHERE `type` = ?
					AND `zone` = ?
					AND `weight` = ?";
			$result = $this->_Db->fetchOne($sql, array($typeId, $zoneId, $weight));
			return !empty($result) ? true : false;
		}
		return true;
	}
	
	
	
	
	
	
	
	
	public function addShipping($array = null) {
		return $this->_Db->insert($this->_table, $array);
	}




    private function _isIdTypeZoneNotEmpty($id = null, $typeId = null, $zoneId = null) {

        return (!empty($id) && !empty($typeId) && !empty($zoneId));

    }
	
	
	
	
	public function getShippingByIdTypeZone($id = null, $typeId = null, $zoneId = null) {
		if ($this->_isIdTypeZoneNotEmpty($id, $typeId, $zoneId)) {
			$sql = "SELECT *
					FROM `{$this->_table}`
					WHERE `id` = ?
					AND `type` = ?
					AND `zone` = ?";
			return $this->_Db->fetchOne($sql, array($id, $typeId, $zoneId));
		}
        return null;
	}
	
	
	
	
	
	
	
	
	
	public function removeShipping($id = null) {
		return $this->delete($id);
	}
	
	
	
	
	
	
	
	private function _isTypeCountryNotEmpty($typeId = null, $countryId = null) {

        return (!empty($typeId) && !empty($countryId));

    }
	
	
	
	
	
	public function getShippingByTypeCountry($typeId = null, $countryId = null) {
		if ($this->_isTypeCountryNotEmpty($typeId, $countryId)) {
			$sql = "SELECT `s`.*,
					IF (
						(
							SELECT COUNT(`weight`)
							FROM `{$this->_table}`
							WHERE `type` = `s`.`type`
							AND `country` = `s`.`country`
							AND `weight` < `s`.`weight`
							ORDER BY `weight` DESC
							LIMIT 0, 1
						) > 0,
						(
							SELECT `weight`
							FROM `{$this->_table}`
							WHERE `type` = `s`.`type`
							AND `country` = `s`.`country`
							AND `weight` < `s`.`weight`
							ORDER BY `weight` DESC
							LIMIT 0, 1
						) + 0.01,
						0
					) AS `weight_from`
				FROM `{$this->_table}` `s`
				WHERE `s`.`type` = ?
				AND `s`.`country` = ?
				ORDER BY `s`.`weight` ASC";
			return $this->_Db->fetchAll($sql, array($typeId, $countryId));
		}
        return null;
	}
	
	
	private function _isTypeCountryWeightNotEmpty($typeId = null, $countryId = null, $weight = null) {

        return (!empty($typeId) && !empty($countryId) && !empty($weight));

    }
	
	
	
	
	
	public function isDuplicateInternational($typeId = null, $countryId = null, $weight = null) {
		if ($this->_isTypeCountryWeightNotEmpty($typeId, $countryId, $weight)) {
			$sql = "SELECT *
					FROM `{$this->_table}`
					WHERE `type` = ?
					AND `country` = ?
					AND `weight` = ?";
			$result = $this->_Db->fetchOne($sql, array($typeId, $countryId, $weight));
			return !empty($result) ? true : false;
		}
		return true;
	}
	
	
	
	private function _isIdTypeCountryNotEmpty($id = null, $typeId = null, $countryId = null) {

        return (!empty($id) && !empty($typeId) && !empty($countryId));

    }
	
	
	
	
	
	
	public function getShippingByIdTypeCountry($id = null, $typeId = null, $countryId = null) {
		if ($this->_isIdTypeCountryNotEmpty($id, $typeId, $countryId)) {
			$sql = "SELECT *
					FROM `{$this->_table}`
					WHERE `id` = ?
					AND `type` = ?
					AND `country` = ?";
			return $this->_Db->fetchOne($sql, array($id, $typeId, $countryId));
		}
        return null;
	}
	
	
	
	
	
	
	
	
	public function addZone($array = null) {
		return $this->_Db->insert($this->_table_3, $array);
	}
	
	
	
	
	
	
	
	
	
	public function removeZone($id = null) {
		return $this->_Db->delete($this->_table_3, $id);
	}
	
	
	
	
	
	
	
	
	
	
	public function updateZone($array = null, $id = null) {
		return $this->_Db->update($this->_table_3, $array, $id);
	}
	
	
	
	private function _isIdZoneNotEmpty($id = null, $zoneId = null) {

        return (!empty($id) && !empty($zoneId));

    }
	
	
	
	
	
	public function getPostCode($id = null, $zoneId = null) {
		if ($this->_isIdZoneNotEmpty($id, $zoneId)) {
			$sql = "SELECT *
					FROM `{$this->_table_4}`
					WHERE `id` = ?
					AND `zone` = ?";
			return $this->_Db->fetchOne($sql, array($id, $zoneId));
		}
        return null;
	}
	
	
	
	
	
	
	
	
	
	public function getPostCodes($zoneId = null) {
		if (!empty($zoneId)) {
			$sql = "SELECT *
					FROM `{$this->_table_4}`
					WHERE `zone` = ?
					ORDER BY `post_code` ASC";
			return $this->_Db->fetchAll($sql, $zoneId);
		}
        return null;
	}
	
	
	
	
	
	
	
	
	public function isDuplicatePostCode($postCode = null) {
		if (!empty($postCode)) {
			$sql = "SELECT *
					FROM `{$this->_table_4}`
					WHERE `post_code` = ?";
			$result = $this->_Db->fetchOne($sql, $postCode);
			return !empty($result) ? true : false;
		}
		return true;
	}
	
	
	
	
	
	
	
	
	public function addPostCode($array = null) {
		return $this->_Db->insert($this->_table_4, $array);
	}
	
	
	
	
	
	
	
	
	
	public function removePostCode($id = null) {
		return $this->_Db->delete($this->_table_4, $id);
	}
	
	
	
	private function _isShippingLocal($user = null) {

        return (

            ($user['same_address'] == 1 && $user['country'] == COUNTRY_LOCAL) ||
            ($user['same_address'] == 0 && $user['ship_country'] == COUNTRY_LOCAL)

        );

    }
	
	
	
	
	
	
	
	public function getShippingOptions($user = null) {
		
		if (!empty($user)) {
			
			$weight = $this->objBasket->weight;
			
			if ($this->_isShippingLocal($user)) {
			
				$postCode = $user['same_address'] == 1 ? $user['post_code'] : $user['ship_post_code'];
				
				$postCode = strtoupper(Helper::alphaNumericalOnly($postCode));
				$zone = $this->getZone($postCode);
				
				// if zone not found return null
				if (empty($zone)) {
					return null;
				}
				
				$zoneId = $zone['zone'];
				
				$sql = "SELECT `t`.*,
						IF (
							? > (
								SELECT MAX(`weight`)
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `zone` = ?
							),
							(
								SELECT `cost`
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `zone` = ?
								ORDER BY `weight` DESC
								LIMIT 0, 1
							),
							(
								SELECT `cost`
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `zone` = ?
								AND `weight` >= ?
								ORDER BY `weight` ASC
								LIMIT 0, 1
							)
						) AS `cost`
						FROM `{$this->_table_2}` `t`
						WHERE `t`.`local` = ?
						AND `t`.`active` = ?
						ORDER BY `t`.`order` ASC";
						
				return $this->_Db->fetchAll($sql, array(
                    $weight, $zoneId, $zoneId, $zoneId, $weight, 1, 1
                ));
				
				
			} else {
				
				$countryId = $user['same_address'] == 1 ? $user['country'] : $user['ship_country'];
				
				$sql = "SELECT `t`.*,
						IF (
							? > (
								SELECT MAX(`weight`)
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `country` = ?
							),
							(
								SELECT `cost`
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `country` = ?
								ORDER BY `weight` DESC
								LIMIT 0, 1
							),
							(
								SELECT `cost`
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `country` = ?
								AND `weight` >= ?
								ORDER BY `weight` ASC
								LIMIT 0, 1
							)
						) AS `cost`
						FROM `{$this->_table_2}` `t`
						WHERE `t`.`local` = ?
						AND `t`.`active` = ?
						ORDER BY `t`.`order` ASC";
						
				return $this->_Db->fetchAll($sql, array(
                    $weight, $countryId, $countryId, $countryId, $weight, 0, 1
                ));
				
			}
			
		}

        return null;
		
	}
	
	
	
	
	
	
	
	
	
	
	public function getZone($postCode = null, $strLen = 4) {
		if (!empty($postCode)) {
			$pc = substr($postCode, 0, $strLen);
			$sql = "SELECT *
					FROM `{$this->_table_4}`
					WHERE `post_code` = ?
					LIMIT 0, 1";
			$result = $this->_Db->fetchOne($sql, $pc);
			if (empty($result) && $strLen > 1) {
				$strLen--;
				return $this->getZone($postCode, $strLen);
			} else {
				return $result;
			}
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	public function getDefault($user = null) {
		
		if (!empty($user)) {
			
			$countryId = $user['same_address'] == 1 ? $user['country'] : $user['ship_country'];
			
			if ($countryId == COUNTRY_LOCAL) {
				
				$sql = "SELECT `t`.*
						FROM `{$this->_table_2}` `t`
						WHERE `t`.`local` = ?
						AND `t`.`active` = ?
						AND `t`.`default` = ?";
						
				return $this->_Db->fetchOne($sql, array(1, 1, 1));
				
			} else {
				
				$sql = "SELECT `t`.*
						FROM `{$this->_table_2}` `t`
						WHERE `t`.`local` = ?
						AND `t`.`active` = ?
						AND `t`.`default` = ?";
						
				return $this->_Db->fetchOne($sql, array(0, 1, 1));
				
			}
			
		}

        return null;
		
	}
	
	
	
	
	
	
	
	public function getShipping($user = null, $shippingId = null) {
		
		if (!empty($user) && !empty($shippingId)) {
			
			$weight = $this->objBasket->weight;
			
			if ($this->_isShippingLocal($user)) {
			
				$postCode = $user['same_address'] == 1 ? $user['post_code'] : $user['ship_post_code'];
				
				$postCode = strtoupper(Helper::alphaNumericalOnly($postCode));
				$zone = $this->getZone($postCode);
				
				// if zone not found return null
				if (empty($zone)) {
					return null;
				}
				
				$zoneId = $zone['zone'];
				
				$sql = "SELECT `t`.*,
						IF (
							? > (
								SELECT MAX(`weight`)
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `zone` = ?
							),
							(
								SELECT `cost`
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `zone` = ?
								ORDER BY `weight` DESC
								LIMIT 0, 1
							),
							(
								SELECT `cost`
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `zone` = ?
								AND `weight` >= ?
								ORDER BY `weight` ASC
								LIMIT 0, 1
							)
						) AS `cost`
						FROM `{$this->_table_2}` `t`
						WHERE `t`.`local` = ?
						AND `t`.`active` = ?
						AND `t`.`id` = ?";
						
				return $this->_Db->fetchOne($sql, array(
                    $weight, $zoneId, $zoneId, $zoneId, $weight, 1, 1, $shippingId
                ));
				
				
			} else {
				
				$countryId = $user['same_address'] == 1 ? $user['country'] : $user['ship_country'];
				
				$sql = "SELECT `t`.*,
						IF (
							? > (
								SELECT MAX(`weight`)
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `country` = ?
							),
							(
								SELECT `cost`
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `country` = ?
								ORDER BY `weight` DESC
								LIMIT 0, 1
							),
							(
								SELECT `cost`
								FROM `{$this->_table}`
								WHERE `type` = `t`.`id`
								AND `country` = ?
								AND `weight` >= ?
								ORDER BY `weight` ASC
								LIMIT 0, 1
							)
						) AS `cost`
						FROM `{$this->_table_2}` `t`
						WHERE `t`.`local` = ?
						AND `t`.`active` = ?
						AND `t`.`id` = ?";
						
				return $this->_Db->fetchOne($sql, array(
                    $weight, $countryId, $countryId, $countryId, $weight, 0, 1, $shippingId
                ));
				
			}
			
		}

        return null;
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}


















