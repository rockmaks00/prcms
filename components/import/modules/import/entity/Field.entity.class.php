<?
class ComponentImport_ModuleImport_EntityField extends Entity
{
    /**
     * Форматирование дат при импорте CSV
     */
    public function setFormattedCreationDate(string $date)
    {
        $raw = date_create_from_format('d-m-Y', $date);
        $this->setCreationDate($raw->format('Y-m-d'));
    }
}
