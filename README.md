# 🛡️ OutSkinLag

**OutSkinLag** es un plugin optimizado para PocketMine-MP API 5 que previene los cambios de skin durante el juego, ayudando a reducir el lag causado por el envío masivo de paquetes `SetSkinPacket`.

---

## 🚀 Características

- 🔒 Bloquea los intentos de cambio de skin después de unirse.
- 🧠 Reduce la sobrecarga de red en servidores con muchos jugadores.
- ⚡ Ligero, sin archivos de configuración ni dependencias externas.
- ✅ Compatible con cualquier sistema de skins preestablecido.

---

## 📦 Requisitos

- [PocketMine-MP](https://pmmp.io/) (API 5)
- PHP 8.1 o superior

---

## 📥 Instalación

1. Descarga el plugin desde la sección [Releases](#).
2. Coloca el archivo `.zip` y descomprime el archivo en la carpeta `/plugins`.
3. Reinicia el servidor.

No se necesita configuración adicional.

---

## 🔧 ¿Cómo funciona?

Intercepta el paquete `PlayerSkinPacket` que se envía al intentar cambiar de skin, cancelándolo automáticamente para evitar que se procese:

```php
public function onDataPacketReceive(DataPacketReceiveEvent $event): void {
    if ($event->getPacket() instanceof PlayerSkinPacket) {
        $event->cancel();
    }
}
