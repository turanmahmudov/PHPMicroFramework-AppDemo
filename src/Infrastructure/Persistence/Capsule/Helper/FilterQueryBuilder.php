<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Capsule\Helper;

class FilterQueryBuilder
{
    public function buildQuery($query, ?string $filter) {
        if ($filter == null) {
            return $query;
        }

        $filters = explode(';', $filter);
        $filters = $this->parseFilters($filters);

        foreach($filters as $filter) {
            $query = $this->addFiltersToQuery($query, $filter);
        }

        return $query;
    }

    private function getOperator($filter) {
        $operatorsPattern = '/=|!=|\(\)|>=|<=|~|>|</';
        $operator = [];
        preg_match($operatorsPattern, $filter, $operator);
        if(count($operator) == 1) {
            return $operator[0];
        }
    }

    private function getFilterValue($filter, $operator) {
        $result = explode($operator, $filter);
        if(count($result) == 2) {
            return $result[1];
        }
    }

    private function getFilterKeys($filter, $operator) {
        $result = explode($operator, $filter);
        if(count($result) == 2) {
            return $result[0];
        }
    }

    private function createRelationTree(string $relationKeys, $operator, $value) {
        $field = $relationKeys;

        return [
            'field' => $field,
            'operator' => $operator,
            'value' => $value
        ];
    }

    private function parseFilters($filters) {
        if(empty($filters)) {
            return [];
        }
        $result = [];
        foreach($filters as $filter) {
            if(empty($filter)) {
                continue;
            }
            $operator = $this->getOperator($filter);
            $value = $this->getFilterValue($filter, $operator);
            $keys = $this->getFilterKeys($filter, $operator);

            array_push($result, [
                'field' => $keys,
                'operator' => $operator,
                'value' => $value
            ]);
        }

        return $result;
    }

    private function addFiltersToQuery(Builder $query, $filters) {
        switch($filters['operator']) {
            case '()':
                return $query->whereIn($filters['field'], explode(',', $filters['value']));
            case '~':
                return $query->where($filters['field'], 'LIKE', '%' . $filters['value'] . '%');
            default:
                return $query->where($filters['field'], $filters['operator'], $filters['value']);
        }
    }
}