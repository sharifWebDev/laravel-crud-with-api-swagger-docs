<?php

namespace App\Dtos;

class StudentDto implements \JsonSerializable
{
    private string $name;

    private int $roll;

    private ?bool $is_active;

    private ?int $created_by;

    private ?int $updated_by;

    private ?string $created_at;

    private ?string $updated_at;

    private ?string $deleted_at;

    public function __construct(array $data)
    {
        $this->setName($data['name'] ?? null);
        $this->setRoll($data['roll'] ?? null);
        $this->setIsActive($data['is_active'] ?? null);
        $this->setCreatedBy($data['created_by'] ?? null);
        $this->setUpdatedBy($data['updated_by'] ?? null);
        $this->setCreatedAt($data['created_at'] ?? null);
        $this->setUpdatedAt($data['updated_at'] ?? null);
        $this->setDeletedAt($data['deleted_at'] ?? null);
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setRoll(int $value): void
    {
        $this->roll = $value;
    }

    public function getRoll(): int
    {
        return $this->roll;
    }

    public function setIsActive(?bool $value): void
    {
        $this->is_active = $value;
    }

    public function getIsActive(): ?bool
    {
        return $this->is_active;
    }

    public function setCreatedBy(?int $value): void
    {
        $this->created_by = $value;
    }

    public function getCreatedBy(): ?int
    {
        return $this->created_by;
    }

    public function setUpdatedBy(?int $value): void
    {
        $this->updated_by = $value;
    }

    public function getUpdatedBy(): ?int
    {
        return $this->updated_by;
    }

    public function setCreatedAt(?string $value): void
    {
        $this->created_at = $value;
    }

    public function getCreatedAt(): ?string
    {
        if ($this->created_at instanceof \DateTimeInterface) {
            return $this->created_at->format('Y-m-d H:i:s');
        }

        if (is_string($this->created_at)) {
            $timestamp = strtotime($this->created_at);
            if ($timestamp !== false) {
                return date('Y-m-d H:i:s', $timestamp);
            }
        }

        return null;
    }

    public function setUpdatedAt(?string $value): void
    {
        $this->updated_at = $value;
    }

    public function getUpdatedAt(): ?string
    {
        if ($this->updated_at instanceof \DateTimeInterface) {
            return $this->updated_at->format('Y-m-d H:i:s');
        }

        if (is_string($this->updated_at)) {
            $timestamp = strtotime($this->updated_at);
            if ($timestamp !== false) {
                return date('Y-m-d H:i:s', $timestamp);
            }
        }

        return null;
    }

    public function setDeletedAt(?string $value): void
    {
        $this->deleted_at = $value;
    }

    public function getDeletedAt(): ?string
    {
        return $this->deleted_at;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getName(),
            'roll' => $this->getRoll(),
            'is_active' => $this->getIsActive(),
            'created_by' => $this->getCreatedBy(),
            'updated_by' => $this->getUpdatedBy(),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt(),
            'deleted_at' => $this->getDeletedAt(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return json_encode($this->toArray(), JSON_PRETTY_PRINT);
    }
}
