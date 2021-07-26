<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-related
 * @version   1.0.17
 * @copyright Copyright (C) 2021 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Related\Model\Rule\Rule\Condition;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;

class QueryBuilder
{
    private $resource;

    private $connection;

    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource   = $resource;
        $this->connection = $resource->getConnection();
    }

    public function joinAttribute(Select $select, Attribute $attribute)
    {
        $code = $attribute->getAttributeCode();

        if ($code == 'category_ids') {
            $table      = $this->resource->getTableName('catalog_category_product');
            $tableAlias = "tbl_{$code}";
            $field      = "{$tableAlias}.category_id";
            $select->joinLeft(
                [$tableAlias => $table],
                "e.entity_id = {$tableAlias}.product_id",
                [$code = $field]
            );

            return $field;
        } elseif ($attribute->isStatic()) {
            $field = "e.{$code}";
            $select->columns([$code => $field]);

            return $field;
        } else {
            $table = $attribute->getBackendTable();

            $tableAlias = "tbl_{$code}";

            $field = "{$tableAlias}.value";

            if (!$this->isJoined($select, $field)) {
                $select->joinLeft(
                    [$tableAlias => $table],
                    "e.entity_id = {$tableAlias}.entity_id AND {$tableAlias}.attribute_id={$attribute->getId()}",
                    [$code => $field]
                );
            }

            return $field;
        }
    }

    public function buildCondition($field, $operator, $value)
    {
        if (in_array($operator, ['()', '!()'])) {
            if (!is_array($value)) {
                $value = explode(',', $value);
            }
        }

        switch ($operator) {
            case '==':
                $condition = $this->conditionEQ($field, $value);
                break;

            case '!=':
                $condition = $this->conditionNEQ($field, $value);
                break;

            case '()':
                $condition = $this->conditionIsOneOf($field, $value);
                break;

            case '!()':
                $condition = $this->conditionNotIsOneOf($field, $value);
                break;

            case '<=>':
                $condition = $this->conditionIsUndefined($field);
                break;

            case '>':
                $condition = $this->conditionGt($field, $value);
                break;

            case '>=':
                $condition = $this->conditionGtEq($field, $value);
                break;

            case '<':
                $condition = $this->conditionLt($field, $value);
                break;

            case '<=':
                $condition = $this->conditionLtEq($field, $value);
                break;

            default:
                throw new \Exception("Undefined operator: {$operator}");
        }


        return $condition;
    }

    private function conditionEQ($field, $value)
    {
        return $this->connection->quoteInto("${field} = ?", $value);
    }

    private function conditionNEQ($field, $value)
    {
        return $this->connection->quoteInto("${field} NOT IN (?)", $value);
    }

    private function conditionGt($field, $value)
    {
        return $this->connection->quoteInto("${field} > ?", $value);
    }

    private function conditionGtEq($field, $value)
    {
        return $this->connection->quoteInto("${field} >= ?", $value);
    }

    private function conditionLt($field, $value)
    {
        return $this->connection->quoteInto("${field} < ?", $value);
    }

    private function conditionLtEq($field, $value)
    {
        return $this->connection->quoteInto("${field} <= ?", $value);
    }

    private function conditionIsOneOf($field, $value)
    {
        $value = array_filter($value);

        $parts = [];
        foreach ($value as $v) {
            $parts[] = $this->connection->quoteInto("FIND_IN_SET(?, {$field})", $v);
        }

        return implode(' OR ', $parts);
    }

    private function conditionNotIsOneOf($field, $value)
    {
        $value = array_filter($value);

        $parts = [];
        foreach ($value as $v) {
            $parts[] = $this->connection->quoteInto("FIND_IN_SET(?, {$field}) = 0", $v);
        }

        return implode(' AND ', $parts);
    }

    private function conditionIsUndefined($field)
    {
        $parts = [
            "{$field} IS NULL",
            "{$field} = ''",
        ];

        return implode(' OR ', $parts);
    }

    private function isJoined(Select $select, $field)
    {
        return strpos($select, $field) !== false;
    }
}
