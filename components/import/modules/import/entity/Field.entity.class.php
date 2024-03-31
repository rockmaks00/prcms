<?
class ComponentImport_ModuleImport_EntityField extends Entity
{
    public function setCreationDate(string $date)
    {
        $raw = date_create_from_format('d-m-Y', $date);
        parent::setCreationDate($raw->format('Y-m-d'));
    }
}
