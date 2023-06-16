import { noti } from "./notifications";

export const copyText = (text) => {
    navigator.clipboard.writeText(text).then(
        () => {
            noti('success', 'Texto copiado con éxito!', 'top-right');
        },
        () => {
          /* clipboard write failed */
            noti('error', 'Error al copiar el texto', 'top-right');
        }
      );
}