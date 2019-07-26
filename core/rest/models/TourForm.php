<?php

namespace rest\models;

use common\models\Agency;
use common\models\Tour;
use common\models\User;
use Exception;
use RuntimeException;
use saghar\address\models\Address;
use saghar\address\models\City;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * TourForm
 *
 * @author Amin Keshavarz <ak_1596@yahoo.com>
 */
class TourForm extends Model
{
    public $name;
    public $start_date;
    public $end_date;
    public $adult_capacity;
    public $child_capacity;
    public $origin_city_id;
    public $origin_address;
    public $origin_lat;
    public $origin_lon;
    public $destination_city_id;
    public $destination_address;
    public $destination_lat;
    public $destination_lon;
    public $tour_type;
    public $agency_id;
    public $leader_id;
    public $status;
    public $adult_unit_price;
    public $child_unit_price;

    /** @var Tour|null */
    private $tourObject;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[
                'name',
                'start_date',
                'end_date',
                'adult_capacity',
                'child_capacity',
                'origin_city_id',
                'destination_city_id',
                'origin_address',
                'destination_address',
                'tour_type',
                'agency_id',
                'leader_id',
                'status',
                'adult_unit_price',
                'child_unit_price'
            ], 'required'],
            [['origin_lon', 'origin_lat', 'destination_lon', 'destination_lat'], 'double'],
            [['start_date', 'end_date'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [[
                'adult_capacity',
                'child_capacity',
                'origin_city_id',
                'destination_city_id',
                'agency_id',
                'leader_id',
                'adult_unit_price',
                'child_unit_price'
            ], 'integer', 'min' => 0],
            [[
                'name',
                'origin_address',
                'destination_address',
            ], 'string', 'max' => 191],
            [['status'], 'in', 'range' => [Tour::STATUS_ACTIVE, Tour::STATUS_PRE_ORDER]],
            [['tour_type'], 'in', 'range' => Tour::TYPE_ALL],
            [['agency_id'], 'exist', 'skipOnError' => true, 'targetClass' => Agency::class, 'targetAttribute' => ['agency_id' => 'id']],
            [['leader_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['leader_id' => 'id']],
            [['origin_city_id', 'destination_city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * Return tour entity errors. (Related to database errors)
     *
     * @return array
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    protected function getTourErrors(): array
    {
        return $this->tourObject && $this->tourObject->hasErrors() ?
            $this->tourObject->getErrors() :
            [];
    }

    /**
     * Save address and return address object.
     *
     * @param int    $cityId
     * @param string $address
     * @param null   $lat
     * @param null   $lon
     *
     * @return \saghar\address\models\Address
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    private function saveAddress(int $cityId, string $address, $lat=null, $lon=null): Address
    {
        $addressModel = new Address([
            'cityId' => $cityId,
            'address' => $address,
            'latitude' => $lat,
            'longitude' => $lon
        ]);
        if ($addressModel->save()) {
            return $addressModel;
        }

        throw new RuntimeException("Can't save address");
    }

    /**
     * Update address and return address object.
     *
     * @param int    $id
     * @param int    $cityId
     * @param string $address
     * @param null   $lat
     * @param null   $lon
     *
     * @return \saghar\address\models\Address
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    private function updateAddress(int $id, int $cityId, string $address, $lat=null, $lon=null): Address
    {
        $addressModel = Address::findOne($id);
        if (!$addressModel) {
            return $this->saveAddress($cityId, $address, $lat, $lon);
        }

        $addressModel->load([
            'cityId' => $cityId,
            'address' => $address,
            'latitude' => $lat,
            'longitude' => $lon
        ], '');

        if ($addressModel->save()) {
            return $addressModel;
        }

        throw new RuntimeException("Can't update address");
    }

    /**
     * @inheritDoc
     */
    public function hasErrors($attribute = null)
    {
        if ($attribute) {
            return parent::hasErrors($attribute);
        }
        return parent::hasErrors($attribute) or !empty($this->getTourErrors()) ? true : false;
    }

    /**
     * @inheritDoc
     */
    public function getErrors($attribute = null)
    {
        if ($attribute) {
            return parent::getErrors($attribute);
        }

        return ArrayHelper::merge(parent::getErrors($attribute), $this->getTourErrors());
    }

    /**
     * Create a new tour.
     *
     * @return Tour|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \Exception
     */
    public function create(): ?Tour
    {
        if (!$this->validate()) {
            return null;
        }

        $tour = new Tour();
        $tour->load($this->getAttributes(), '');

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            $origin = $this->saveAddress(
                $this->origin_city_id,
                $this->origin_address,
                $this->origin_lat,
                $this->origin_lon
            );
            $destination = $this->saveAddress(
                $this->destination_city_id,
                $this->destination_address,
                $this->destination_lat,
                $this->destination_lon
            );

            $tour->origin_id = $origin->id;
            $tour->destination_id = $destination->id;

            $res = $tour->save();
            if ($res) {
                $transaction->commit();
                return $tour;
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return null;
    }

    /**
     * Update tour.
     *
     * @param int $id Tour id.
     *
     * @return Tour|null
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     * @throws \Exception
     */
    public function update(int $id): ?Tour
    {
        $tour = Tour::findOne($id);
        if (!$this->validate() or empty($tour)) {
            return null;
        }

        $tour->load($this->getAttributes(), '');

        $transaction = Yii::$app->getDb()->beginTransaction();
        try {
            $origin = $this->updateAddress(
                $tour->origin_id,
                $this->origin_city_id,
                $this->origin_address,
                $this->origin_lat,
                $this->origin_lon
            );
            $destination = $this->updateAddress(
                $tour->destination_id,
                $this->destination_city_id,
                $this->destination_address,
                $this->destination_lat,
                $this->destination_lon
            );

            $tour->origin_id = $origin->id;
            $tour->destination_id = $destination->id;

            $res = $tour->save();
            if ($res) {
                $transaction->commit();
                return $tour;
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            throw $exception;
        }

        return null;
    }

    /**
     * Cancel tour.
     *
     * @param int $id
     *
     * @return bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function cancelTour(int $id): bool
    {
        $tour = Tour::findOne($id);
        return $tour and $tour->updateAttributes(['status' => Tour::STATUS_CANCEL]);
    }

    /**
     * Finish tour.
     *
     * @param int $id
     *
     * @return bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function finishTour(int $id): bool
    {
        $tour = Tour::findOne($id);
        return $tour and $tour->updateAttributes(['status' => Tour::STATUS_FINISH]);
    }

    /**
     * Active tour.
     *
     * @param int $id
     *
     * @return bool
     *
     * @author Amin Keshavarz <ak_1596@yahoo.com>
     */
    public static function activeTour(int $id): bool
    {
        $tour = Tour::findOne($id);
        return $tour and $tour->updateAttributes(['status' => Tour::STATUS_ACTIVE]);
    }
}
