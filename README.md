# 🍽️ Mesa&Sabor
### Sistema de Gestión de Restaurante Online

**Proyecto académico | Programación Web Avanzada**

Mesa&Sabor es una aplicación web desarrollada para digitalizar y optimizar la administración de un restaurante, integrando en una sola plataforma la gestión del menú gastronómico, el registro de usuarios, la realización de pedidos y el seguimiento de su preparación y entrega.

El sistema cuenta con una interfaz moderna, intuitiva y adaptable a diferentes dispositivos, además de un sistema de autenticación y control de acceso según los roles de cada usuario.

### 🌐 Acceso al proyecto

**[Visitar Mesa&Sabor — Sitio web](https://zahiddev.infinityfreeapp.com/pwatarea6/public/index.php)**

**[Ver código fuente en GitHub](https://github.com/Team-Evorion/pwatarea6)**

---

## 🎯 Objetivo del proyecto

Desarrollar un sistema web de gestión gastronómica que permita administrar los procesos principales de un restaurante, aplicando el patrón de arquitectura **Modelo–Vista–Controlador (MVC)**, programación del lado del servidor, bases de datos relacionales y mecanismos de seguridad basados en roles.

La aplicación busca facilitar la interacción entre clientes, personal de cocina, camareros y administradores mediante un flujo de trabajo organizado.

## ⚙️ Funcionalidades principales

**1. Catálogo gastronómico interactivo**

- Visualización de platos con fotografías, descripciones y precios.
- Búsqueda de platos por nombre o descripción.
- Filtros por rangos de precios.
- Selección de cantidades y cálculo automático del total.
- Diseño responsive para computadoras, tabletas y dispositivos móviles.

**2. Gestión de platos**

- Registro de nuevos platos.
- Subida obligatoria de fotografías.
- Edición de nombres, descripciones, precios e imágenes.
- Eliminación de platos sin pedidos asociados.
- Validación de datos y formatos de imagen.

**3. Gestión de usuarios**

- Registro e inicio de sesión.
- Autenticación mediante contraseñas cifradas.
- Administración de cuentas y asignación de roles.
- Edición de información de usuarios.
- Restricción de acceso a funcionalidades según los permisos asignados.

**4. Gestión de pedidos**

- Selección y confirmación de pedidos desde el catálogo.
- Registro de múltiples platos en una misma solicitud.
- Historial de pedidos por cliente.
- Seguimiento del estado de cada plato solicitado.
- Conservación de los precios registrados al momento de realizar cada pedido.

**5. Panel administrativo**

- Visualización de estadísticas generales.
- Gestión de usuarios y productos.
- Supervisión de pedidos.
- Consulta de pedidos por estado.
- Seguimiento de la actividad del restaurante.

## 👥 Roles del sistema

Mesa&Sabor implementa cuatro roles principales, cada uno con responsabilidades y permisos específicos.

| Rol | Funcionalidades |
|---|---|
| **Administrador** | Gestionar usuarios y platos, supervisar pedidos y consultar estadísticas generales. |
| **Cliente** | Explorar el menú, seleccionar platos, realizar pedidos y consultar su historial. |
| **Chef** | Visualizar pedidos pendientes, iniciar la preparación y marcar platos como listos para servir. |
| **Camarero** | Registrar pedidos para clientes, consultar platos listos y marcar su entrega como servida. |

El sistema protege las rutas y operaciones mediante mecanismos de autenticación y autorización.

## 🔄 Flujo de gestión de pedidos

Los pedidos siguen cuatro estados principales:

**Pendiente → En preparación → Listo para servir → Servido**

El proceso comienza cuando el cliente confirma su pedido. Posteriormente, el chef gestiona la preparación y el camarero registra la entrega.

Cada cambio de estado puede consultarse desde las interfaces correspondientes, permitiendo un seguimiento organizado de la operación gastronómica.

## 💻 Tecnologías utilizadas

| Tecnología | Aplicación |
|---|---|
| **PHP 8.3+** | Desarrollo del backend. |
| **Laravel 13** | Framework principal de la aplicación. |
| **MySQL** | Almacenamiento y gestión de datos. |
| **Eloquent ORM** | Interacción con la base de datos. |
| **Blade** | Construcción de vistas dinámicas. |
| **HTML5 y CSS3** | Estructura y presentación visual. |
| **Bootstrap 5** | Diseño adaptable e interfaces. |
| **JavaScript** | Interactividad del catálogo y selección de platos. |
| **PHPUnit** | Pruebas automatizadas. |
| **Git y GitHub** | Control de versiones y repositorio. |
| **InfinityFree** | Publicación y alojamiento del sistema web. |

### Arquitectura de desarrollo

El proyecto aplica el patrón **MVC (Modelo–Vista–Controlador)**, separando la gestión de datos, la presentación visual y el procesamiento de solicitudes.

También incorpora servicios para la lógica de negocio, validaciones de formularios, middleware para control de permisos y operaciones sobre una base de datos relacional.

## 🔐 Seguridad y validación

Se implementaron mecanismos para proteger la información y controlar el acceso al sistema:

- Almacenamiento seguro de contraseñas mediante hashing.
- Protección CSRF en formularios.
- Validaciones de datos del lado del servidor.
- Acceso restringido según el rol.
- Protección de los pedidos de cada cliente.
- Restricciones en las transiciones de estados.
- Prevención de eliminaciones que comprometan la integridad de pedidos existentes.

## 🧪 Pruebas y funcionamiento

El proyecto fue sometido a pruebas funcionales y automatizadas para comprobar los procesos principales.

**Resultados de las pruebas automatizadas:**

- **14 pruebas aprobadas.**
- **52 aserciones verificadas.**

Las pruebas abarcaron autenticación, permisos, gestión de platos, fotografías, creación de pedidos y cambios de estado.

Además, se realizaron comprobaciones manuales del funcionamiento del catálogo, los roles y las funcionalidades del sistema.

### Publicación en InfinityFree

La aplicación se encuentra publicada en InfinityFree y utiliza una base de datos MySQL.

Se implementó una configuración de almacenamiento que permite visualizar las fotografías y guardar nuevas imágenes directamente en una carpeta pública del hosting, sin depender de enlaces simbólicos.

## 👨‍💻 Integrantes del equipo

**Team-Evorion**

*Integrantes organizados alfabéticamente por nombre:*

1. Elkin Velastegui
2. Jesús Fernández
3. Jose Merlin
4. José Rosado
5. Terry Ponce
6. Zahid Zambrano

## 🎓 Información académica

**Universidad:** Universidad Bolivariana del Ecuador (UBE)

**Asignatura:** Programación Web Avanzada

**Proyecto:** Sistema de Gestión de Restaurante Online

**Equipo de desarrollo:** Team-Evorion

**Modalidad:** Proyecto académico grupal

## 📌 Consideraciones finales

Mesa&Sabor constituye una aplicación web funcional orientada a demostrar la implementación de sistemas de gestión mediante Laravel y MySQL.

El proyecto integra distintas operaciones de un restaurante en una plataforma centralizada, aplicando buenas prácticas de organización del código, validación de información y separación de permisos.

La aplicación fue desarrollada con fines académicos. No incluye procesamiento de pagos reales.

---

**🍽️ Mesa&Sabor — Una experiencia gastronómica conectada, desde el menú hasta tu mesa.**

**[Acceder al sistema](https://zahiddev.infinityfreeapp.com/pwatarea6/public/index.php)** | **[Repositorio GitHub](https://github.com/Team-Evorion/pwatarea6)**