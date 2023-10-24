# Stormwind

Librería que contiene herramientas útiles para desarrollo de plugins de reconocimiento facial haciendo uso de AWS Rekognition.

### Instalación

```bash
composer requrie aws-project/stormwind:dev-main
```

### Testeo

Para ejecutar pruebas unitarias de esta librería es necesario crear un archivo `.env` dentro de la carpeta `tests` que siga el siguiente formato:

```.env
AWS_REGION = aws_region
AWS_PUBLIC_KEY = aws_public_key
AWS_SECRET_KEY = aws_secret_key
# Dejar tal cual esta última variable de entorno
TEST=TEST
```

##### Ejecución de pruebas unitarias

```bash
./vendor/bin/phpunit tests
```
o
```bash
composer run tests
```