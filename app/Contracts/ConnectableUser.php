<?php
namespace App\Contracts;

interface ConnectableUser {
  public function updateConnectId($id): void;
  public function getConnectId(): string;
  public function getEmail(): string;
}