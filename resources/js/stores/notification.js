import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useNotificationStore = defineStore('notification', () => {
  const message = ref('');
  const type = ref('info'); // success, error, info, warning
  const visible = ref(false);
  let timeout = null;

  function show(msg, msgType = 'info', duration = 3000) {
    message.value = msg;
    type.value = msgType;
    visible.value = true;

    if (timeout) clearTimeout(timeout);
    timeout = setTimeout(() => {
      visible.value = false;
    }, duration);
  }

  return { message, type, visible, show };
});
