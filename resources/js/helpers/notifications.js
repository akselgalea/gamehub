import { useToast, POSITION } from 'vue-toastification';

const toast = useToast();

export function noti(type, message, position = 'top-right') {
  toast[type](message, { position: position });
}