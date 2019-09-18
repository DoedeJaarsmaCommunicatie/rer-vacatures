<?php
namespace PropertyPeople\Vendor;

interface Type
{
    public function labels(): array;
    public function args(): array;
    public function register(): void;
    public function action(): void;
    
    public function defaultArgs(): array;
    public function defaultLabels(): array;
    
    public function slug(): string;
    public function namePlural(): string;
    public function name(): string;
    
    public function capabilityType(): string;
    public function publicQuery(): bool;
    public function exclude(): bool;
    public function archive();
    
    public function export(): bool;
    public function addToNav(): bool;
    public function showInAdmin(): bool;
    public function menuPosition(): int;
    
    public function showInMenu(): bool;
    public function showInUi(): bool;
    public function public(): bool;
    public function hierarchical(): bool;
    
    public function taxonomies();
    public function supports();
    
    /* Required per Post type */
    public function description(): string;
    public function label(): string;
    
    public function textDomain(): string;
}
