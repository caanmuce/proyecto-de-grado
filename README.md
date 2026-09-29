# EducaSex

> **Plataforma educativa para promover el acceso responsable a información sobre sexualidad, salud reproductiva y bienestar emocional.**
>
> Proyecto de investigación y desarrollo realizado durante los grados **10.º y 11.º**, como una propuesta tecnológica con impacto educativo que me permitió graduarme con uno de los mejores proyectos de investigación del colegio.

[![PHP](https://img.shields.io/badge/PHP-MySQL-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![Frontend](https://img.shields.io/badge/Frontend-Bootstrap%204-7952B3?logo=bootstrap&logoColor=white)](https://getbootstrap.com/)
[![IA](https://img.shields.io/badge/IA-Gemini-4285F4?logo=google&logoColor=white)](https://ai.google.dev/)
[![Estado](https://img.shields.io/badge/estado-proyecto%20académico-f0ad4e)](#estado-del-proyecto)

## Tabla de contenido

- [Sobre el proyecto](#sobre-el-proyecto)
- [Problema y propósito](#problema-y-propósito)
- [Funcionalidades](#funcionalidades)
- [Roles de usuario](#roles-de-usuario)
- [Arquitectura y flujo](#arquitectura-y-flujo)
- [Tecnologías](#tecnologías)
- [Estructura del repositorio](#estructura-del-repositorio)
- [Requisitos](#requisitos)
- [Instalación local](#instalación-local)
- [Configuración de Gemini](#configuración-de-gemini)
- [Base de datos](#base-de-datos)
- [Estado del proyecto](#estado-del-proyecto)
- [Seguridad y próximos pasos](#seguridad-y-próximos-pasos)
- [Contexto académico](#contexto-académico)
- [Créditos y licencia](#créditos-y-licencia)

## Sobre el proyecto

**EducaSex** es una aplicación web orientada a estudiantes y a la comunidad educativa. Reúne en un mismo espacio recursos de aprendizaje, participación en foros, acompañamiento psicológico, solicitudes de citas y un chatbot de orientación inicial.

La idea central es acercar información clara y herramientas de acompañamiento a los jóvenes mediante una plataforma que combine contenidos educativos, interacción social y tecnología. El proyecto fue construido progresivamente como trabajo de investigación y desarrollo escolar, pasando de la identificación de una necesidad del entorno a la creación de un prototipo funcional.

> EducaSex es una herramienta educativa y de orientación inicial. No reemplaza la atención médica, psicológica ni los servicios de emergencia.

## Problema y propósito

Durante la adolescencia pueden existir dudas sobre sexualidad, consentimiento, prevención, relaciones, salud reproductiva y bienestar emocional. Sin orientación confiable y accesible, esas dudas pueden resolverse mediante información incompleta o poco segura.

EducaSex busca:

- Facilitar el acceso a recursos educativos organizados.
- Crear un espacio moderable para preguntas y conversaciones.
- Permitir solicitar acompañamiento mediante citas.
- Ofrecer una primera orientación conversacional con SEIN, el chatbot del proyecto.
- Dar a administradores y profesionales herramientas para gestionar usuarios, contenidos y solicitudes.

## Funcionalidades

### Acceso y usuarios

- Inicio de sesión mediante número de documento y contraseña.
- Registro de usuarios con datos personales, contacto y rol.
- Sesiones PHP para conservar la identidad durante la navegación.
- Perfil y administración de usuarios desde el panel.

### Foros y participación

- Consulta de foros disponibles.
- Creación de publicaciones con título, contenido y categoría.
- Vista detallada y comentarios.
- Gestión administrativa de publicaciones.

### Recursos educativos

- Consulta de materiales educativos.
- Clasificación por tipo y categoría.
- Creación y edición de recursos.
- Carga de archivos en `uploads/`.

### Acompañamiento

- Agendamiento de citas indicando fecha, hora y motivo.
- Consulta, actualización y eliminación de citas para los perfiles autorizados.
- Casos de estudio para explorar situaciones relacionadas con la toma de decisiones y el bienestar.

### Chatbot SEIN

El chatbot integra varias estrategias de respuesta:

1. Registra la conversación.
2. Busca información relevante en una base de conocimiento.
3. Usa una respuesta almacenada cuando la coincidencia tiene suficiente confianza.
4. Consulta Gemini para preguntas que requieren una respuesta generativa.
5. Aplica respuestas de respaldo si la API externa no está disponible.
6. Registra métricas, comportamiento y retroalimentación para análisis posterior.

La interfaz principal se encuentra en `Admin/modulo/chatbot.php` y la API unificada en `Admin/modulo/chatbot_api.php`. También existe una implementación anterior o alternativa en `Admin/modulo/api_chatbot.php`.

### Secciones en desarrollo

`interacciones.php`, `mensajes.php`, `configuracion.php` y `centro_actividad.php` incluyen interfaces o contenido demostrativo. Se conservan como parte de la evolución del proyecto, pero no deben interpretarse como módulos completamente conectados a un flujo de producción.

## Roles de usuario

| Rol | Valor | Capacidades principales |
|---|---:|---|
| Administrador | `1` | Gestionar usuarios, foros, recursos y citas. |
| Estudiante | `2` | Consultar recursos, participar en foros, usar el chatbot y solicitar citas. |
| Psicólogo | `3` | Apoyar la gestión de usuarios, contenidos y citas según el módulo. |

La navegación adapta los enlaces visibles según el rol. Antes de publicar el sistema, las autorizaciones deben reforzarse también en el servidor para cada operación.

## Arquitectura y flujo

La aplicación utiliza una arquitectura PHP tradicional, con páginas que procesan formularios, consultan MySQL y renderizan HTML.

```text
Usuario
  |
  v
index.php  --->  autenticación y sesión
  |
  v
Admin/dashboard.php  --->  carga módulos mediante ?mod=...
  |
  +--> usuarios       ---> usuarios / roles
  +--> foros          ---> foro / comentarios / categorias
  +--> recursos       ---> recursos_educativos / tipo / categoria
  +--> citas          ---> citas
  +--> chatbot SEIN   ---> messages / knowledge_base / Gemini
  +--> casos de estudio e interfaces sociales
```

Archivos de procesamiento principales:

- `conexion.php`: conexión MySQLi y codificación `utf8mb4`.
- `codigo.php`: registro de usuarios.
- `cod_foro.php`: creación de foros.
- `cod_recurso.php`: creación y carga de recursos.
- `cod_cita.php`: registro de citas.
- `exit.php`: cierre de sesión.

## Tecnologías

- **PHP** con sesiones y MySQLi.
- **MySQL o MariaDB** para persistencia.
- **HTML, CSS y JavaScript** para la interfaz.
- **Bootstrap 4** y **SB Admin 2** para el panel administrativo.
- **jQuery**, **Font Awesome**, **Chart.js**, **DataTables** y **jQuery Easing**.
- **Gulp** para recompilar los recursos del tema administrativo.
- **Gemini API** como servicio opcional de generación de respuestas.

El tema SB Admin 2 y sus dependencias se encuentran principalmente dentro de `Admin/`. Su documentación original está en [Admin/README.md](Admin/README.md).

## Estructura del repositorio

```text
.
├── index.php                 # Inicio de sesión
├── registrar.php             # Formulario de registro
├── codigo.php                # Procesamiento de usuarios
├── conexion.php              # Configuración de MySQL
├── cod_foro.php              # Procesamiento de foros
├── cod_recurso.php           # Procesamiento de recursos
├── cod_cita.php              # Procesamiento de citas
├── exit.php                  # Cierre de sesión
├── Admin/
│   ├── dashboard.php         # Panel y enrutador de módulos
│   ├── login.php             # Acceso alternativo del panel
│   ├── modulo/               # Funcionalidades de EducaSex
│   ├── css/                  # Estilos del panel
│   ├── js/                   # Scripts y gráficos
│   ├── scss/                 # Fuentes SCSS del tema
│   └── vendor/               # Dependencias frontend incluidas
├── uploads/                  # Archivos de recursos educativos
├── css/                      # Recursos Bootstrap adicionales
├── js/                       # Scripts Bootstrap adicionales
├── style/                    # Estilos complementarios
├── img/                      # Imágenes y recursos visuales
└── web_inf/                  # Sitio informativo basado en una plantilla separada
```

También hay archivos `.zip` y `.7z` con copias o recursos históricos del desarrollo. No son necesarios para ejecutar la aplicación principal.

## Requisitos

- Apache, XAMPP, WAMP o un servidor compatible con PHP.
- PHP con las extensiones `mysqli`, `curl`, `json`, `mbstring` y sesiones habilitadas.
- MySQL o MariaDB.
- Un navegador web moderno.
- La base de datos `educasex` con las tablas requeridas.
- Una clave de Gemini solo si se desea activar la generación mediante IA.
- Permisos de escritura para `uploads/` y para los archivos de registro del chatbot.

## Instalación local

> El repositorio no incluye actualmente un archivo `.sql` o migraciones. La creación o recuperación del esquema de base de datos es un paso necesario antes de iniciar sesión.

1. Instala XAMPP, WAMP o un entorno equivalente con Apache, PHP y MySQL.
2. Copia el proyecto dentro de la carpeta pública del servidor, por ejemplo `htdocs/proyecto-de-grado`.
3. Crea una base de datos llamada `educasex` en MySQL/MariaDB.
4. Importa el esquema y los datos iniciales que correspondan al proyecto. Como mínimo, revisa las tablas `usuarios`, `rol`, `foro`, `comentarios`, `citas`, `recursos_educativos`, `tipo`, `categoria`, `messages` y `knowledge_base`.
5. Ajusta las credenciales en `conexion.php` según tu entorno. La configuración incluida espera:

   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $dbname = "educasex";
   ```

6. Verifica que Apache y MySQL estén activos.
7. Abre `http://localhost/proyecto-de-grado/index.php`.
8. Registra o crea un usuario de prueba y comprueba el acceso al panel.

Para trabajar en los recursos del tema administrativo desde `Admin/`:

```bash
cd Admin
npm install
npm start
```

Ese flujo de Node/Gulp solo es necesario para recompilar o modificar los assets del tema. La aplicación PHP ya incluye dependencias frontend estáticas en `Admin/vendor/`.

## Configuración de Gemini

Define la clave como variable de entorno y no la escribas en el código fuente:

### Windows PowerShell

```powershell
$env:GEMINI_API_KEY = "TU_CLAVE"
```

### Linux o macOS

```bash
export GEMINI_API_KEY="TU_CLAVE"
```

El proyecto también contempla `OPENAI_API_KEY` como nombre de respaldo por compatibilidad histórica, pero la configuración recomendada es `GEMINI_API_KEY`.

Archivos de comprobación disponibles:

- `Admin/modulo/check_env.php`: revisa si la variable de entorno está disponible.
- `Admin/modulo/check_key.php`: muestra el estado de la clave.
- `Admin/modulo/test_gemini.php`: prueba el flujo del chatbot.
- `Admin/modulo/test_chat.html`: prueba manual de la interfaz.

No dejes estas herramientas de diagnóstico expuestas en un servidor público.

## Base de datos

La conexión central está en `conexion.php` y usa `utf8mb4`. Además de las tablas funcionales, la API del chatbot puede crear automáticamente algunas tablas auxiliares:

- `ml_models`
- `user_behavior`
- `knowledge_weights`

El flujo avanzado también espera tablas como `conversation_feedback` y `conversation_analytics`, dependiendo de las acciones utilizadas. Es importante validar el esquema completo antes de desplegar.

## Estado del proyecto

EducaSex es un **proyecto académico funcional y demostrativo**. El código permite recorrer los flujos principales, pero todavía requiere una fase de endurecimiento antes de considerarse un sistema listo para producción.

### Implementado o demostrable

- Autenticación y sesiones.
- Registro y roles.
- Panel administrativo.
- Foros y comentarios.
- Recursos educativos con archivos.
- Solicitud y gestión de citas.
- Chatbot con base de conocimiento, Gemini y respuestas de respaldo.
- Casos de estudio e interfaces complementarias.

### Pendiente de consolidación

- Entregar o documentar el esquema SQL inicial.
- Unificar las dos implementaciones de la API del chatbot.
- Completar la persistencia de mensajes, interacciones y configuraciones.
- Incorporar pruebas automatizadas y una guía de despliegue.
- Actualizar las interfaces heredadas de plantillas externas para que toda la identidad visual corresponda a EducaSex.

## Seguridad y próximos pasos

Antes de usar el proyecto fuera de un entorno académico controlado, se recomienda:

- Migrar `md5` a `password_hash()` y `password_verify()`.
- Usar consultas preparadas en todos los formularios y módulos.
- Implementar protección CSRF.
- Validar permisos en cada endpoint, no solo ocultando enlaces del menú.
- Restringir la asignación pública de roles privilegiados.
- Validar extensión, MIME, tamaño y nombre de cada archivo subido.
- Desactivar `DEBUG` y `display_errors` en producción.
- Proteger o retirar los archivos de diagnóstico y los logs.
- Revisar la exposición de datos personales, conversaciones y citas.
- Configurar una política de privacidad y un aviso de uso responsable para menores.

Estas observaciones no disminuyen el valor del proyecto como experiencia de investigación; señalan el trabajo necesario para pasar de un prototipo escolar a una aplicación institucional segura y mantenible.

## Contexto académico

Este proyecto representa un proceso de aprendizaje desarrollado entre **10.º y 11.º grado**. Su valor no está únicamente en las páginas construidas, sino también en el recorrido que integra:

- Investigación de una necesidad cercana a la comunidad estudiantil.
- Diseño de una propuesta de orientación y acceso a información.
- Aprendizaje de PHP, bases de datos, sesiones, interfaces web y consumo de APIs.
- Iteración de módulos a partir de pruebas y nuevas ideas.
- Presentación de una solución tecnológica con propósito social.

EducaSex fue una oportunidad para convertir una inquietud del entorno escolar en un proyecto de investigación aplicado, y fue reconocido como uno de los mejores proyectos de investigación del colegio dentro del proceso de graduación.

## Créditos y licencia

- El código y la idea de EducaSex pertenecen al proyecto académico de su autor.
- La carpeta `Admin/` utiliza **SB Admin 2**, un tema de Start Bootstrap distribuido bajo licencia MIT. Consulta [Admin/LICENSE](Admin/LICENSE).
- Las dependencias frontend conservan sus propias licencias dentro de `Admin/vendor/` y `Admin/package.json`.
- `web_inf/` contiene una plantilla informativa separada con sus propios archivos de licencia y atribución.

---

**EducaSex** · Proyecto de investigación escolar · 10.º y 11.º grado
