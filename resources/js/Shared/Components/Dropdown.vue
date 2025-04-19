<template>
    <div class="relative inline-block text-left" @keydown.esc="open = false">
      <!-- Trigger -->
      <div @click="toggle">
        <slot name="trigger" />
      </div>

      <!-- Dropdown content -->
      <transition
        enter-active-class="transition ease-out duration-100"
        enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100"
        leave-active-class="transition ease-in duration-75"
        leave-from-class="transform opacity-100 scale-100"
        leave-to-class="transform opacity-0 scale-95"
      >
        <div
          v-show="open"
          class="absolute z-50 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none right-0"
          @click.outside="open = false"
        >
          <slot name="content" />
        </div>
      </transition>
    </div>
  </template>

  <script setup>
  import { ref, onMounted, onBeforeUnmount } from 'vue'

  const open = ref(false)
  function toggle() {
    open.value = !open.value
  }

  // Close on click outside
  function onClickOutside(e) {
    if (!e.target.closest('.dropdown-root')) open.value = false
  }
  onMounted(() => document.addEventListener('click', onClickOutside))
  onBeforeUnmount(() => document.removeEventListener('click', onClickOutside))
  </script>

  <style scoped>
  /* Optional: root class if you want to style specific parts */
  </style>
