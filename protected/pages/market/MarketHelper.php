<?php
include_once("ShopEquipment.php");
include_once("ShopWeaponEquipment.php");
include_once("ShopArmorEquipment.php");
class MarketHelper
{
    public static function getAllPurchasableWeapons() {
		return EquipmentRecord::finder()->findAllByTypeAndIsPurchasable(EquipmentRecord::WEAPON_TYPE, TRUE);
	}
	
	public static function getAllSellableWeapons() {
		return EquipmentRecord::finder()->findAllByTypeAndIsSellable(EquipmentRecord::WEAPON_TYPE, TRUE);
	}
	
	public static function getEquipmentRecord($equipmentId) {
        $equipmentRecord = EquipmentRecord::finder()->findByPk($equipmentId);
        if ($equipmentRecord === null) {
            throw new DSException(500, 'equipment_id_invalid', $equipmentId);
		}
        return $equipmentRecord;
	}
	
	public static function getPersoEquipmentRecord($persoId, $equipmentId) {
        $persoEquipmentRecord = PersoEquipmentRecord::finder()->findByPersoIdAndEquipmentId($persoId, $equipmentId);
        if ($persoEquipmentRecord === null) {
            throw new DSException(500, 'perso_equipment_id_invalid', $equipmentId);
		}
        return $persoEquipmentRecord;
	}
	
	public static function getAllSellableEquipmentFromPerso($persoId) {
        $data = PersoEquipmentRecord::finder()->findAllByPersoId($persoId);
		$data2 = new TList();
		foreach ($data as $persoEquipment) {
			if ($persoEquipment->equipment->isSellable == TRUE) {
				$data2->add($persoEquipment);
			}
		}
        return $data2;
	}
	
	public static function getShop($shopId) {
		$shopRecord = ShopRecord::finder()->findByPk($shopId);
		if ($shopRecord === NULL) {
			throw new DSException(500, 'shop_id_invalid', $shopId);
		}
		return $shopRecord;
	}

	public static function getAllPurchasableItemsForShop($shopId, $type)
	{
		if ($type == EquipmentRecord::WEAPON_TYPE)
		{
			$sql = "SELECT equipment.*, weapon.*, shopEquipment.* ".
				"FROM equipment ".
				"INNER JOIN weapon ON equipment.id = weapon.id ".
				"INNER JOIN shopEquipment ON equipment.id = shopEquipment.equipmentId ".
				"INNER JOIN shop ON shopEquipment.shopId = shop.id ".
				"WHERE shop.id = " . $shopId . " AND equipment.type = " . $type;
			$data = TActiveRecord::finder('ShopWeaponEquipment')->findAllBySql($sql);
		}
		else if ($type == EquipmentRecord::ARMOR_TYPE)
		{
			$sql = "SELECT equipment.*, armor.*, shopEquipment.* ".
				"FROM equipment ".
				"INNER JOIN armor ON equipment.id = armor.id ".
				"INNER JOIN shopEquipment ON equipment.id = shopEquipment.equipmentId ".
				"INNER JOIN shop ON shopEquipment.shopId = shop.id ".
				"WHERE shop.id = " . $shopId . " AND equipment.type = " . $type;
			$data = TActiveRecord::finder('ShopAmorEquipment')->findAllBySql($sql);
		}
		else
		{
			$sql = "SELECT equipment.*, shopEquipment.* ".
				"FROM equipment ".
				"INNER JOIN shopEquipment ON equipment.id = shopEquipment.equipmentId ".
				"INNER JOIN shop ON shopEquipment.shopId = shop.id ".
				"WHERE shop.id = " . $shopId . " AND equipment.type = " . $type;
			$data = TActiveRecord::finder('ShopEquipment')->findAllBySql($sql);
		}

		return $data;
	}
}
?>