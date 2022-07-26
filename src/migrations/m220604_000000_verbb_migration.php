<?php
namespace verbb\doxter\migrations;

use verbb\doxter\fields\Doxter as DoxterField;

use Craft;
use craft\db\Migration;

class m220604_000000_verbb_migration extends Migration
{
    // Public Methods
    // =========================================================================

    public function safeUp(): bool
    {
        $this->update('{{%fields}}', ['type' => DoxterField::class], ['type' => 'selvinortiz\doxter\fields\DoxterField']);

        // Don't make the same config changes twice
        $projectConfig = Craft::$app->getProjectConfig();
        $schemaVersion = $projectConfig->get('plugins.doxter.schemaVersion', true);

        if (version_compare($schemaVersion, '4.0.0', '>=')) {
            return true;
        }

        $fields = $projectConfig->get('fields') ?? [];

        $fieldMap = [
            'selvinortiz\\doxter\\fields\\DoxterField' => DoxterField::class,
        ];

        foreach ($fields as $fieldUid => $field) {
            $type = $field['type'] ?? null;

            if (isset($fieldMap[$type])) {
                $field['type'] = $fieldMap[$type];

                $projectConfig->set('fields.' . $fieldUid, $field);
            }
        }

        return true;
    }

    public function safeDown(): bool
    {
        echo "m220604_000000_verbb_migration cannot be reverted.\n";
        return false;
    }
}