# Code Rhapsodie Connector Mistral Bundle

The code-rhapsodie/connector-mistral bundle integrates Mistral into Ibexa DXP, enabling AI-assisted content generation and editing capabilities directly from the Ibexa Back Office.

## Installation

### Step 1: Install the bundle via composer
```bash
  composer require code-rhapsodie/connector-mistral
```

### Step 2: Enable the bundle
````php
// config/bundles.php

return [
    // ...
    CodeRhapsodie\Bundle\ConnectorMistral\CRConnectorMistralBundle::class => ['all' => true],
];
````

### Step 3: Configure your api key
```dotenv
#.env

MISTRAL_API_KEY=your-google-mistral-api-key-here
```

### Step 4: Import generic IA Action migration
```bash
  php bin/console ibexa:migrations:import vendor/code-rhapsodie/connector-mistral/src/bundle/Resources/migrations/mistral_action_configurations.yaml
```

### Step 5: Execute Ibexa migration
```bash
  php bin/console ibexa:migrations:migrate
```
