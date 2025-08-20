<template>
  <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 group">
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-4">
        <div :class="[icon_color, 'p-3 rounded-lg bg-opacity-10', icon_bg]">
          <i :class="['fas', icon, 'text-xl']"></i>
        </div>
        <div>
          <h3 class="text-gray-600 text-sm font-medium uppercase tracking-wide">{{ label }}</h3>
          <p class="text-2xl font-bold text-gray-800 mt-1">{{ value }}</p>
        </div>
      </div>
      <div :class="[color_border, 'w-1 h-12 rounded-full']"></div>
    </div>
    <div class="mt-4 pt-4 border-t border-gray-100">
      <div class="flex items-center justify-between text-sm">
        <span class="text-gray-500">Status</span>
        <span :class="[status_color, 'font-medium']">{{ status_text }}</span>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TileCard",
  props: {
    icon: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    value: {
      type: String,
      required: true,
    },
    color: {
      type: String,
      default: "emerald",
    },
  },
  computed: {
    icon_color() {
      return `text-${this.color}-600`;
    },
    icon_bg() {
      return `bg-${this.color}-100`;
    },
    color_border() {
      return `bg-${this.color}-500`;
    },
    status_color() {
      return `text-${this.color}-600`;
    },
    status_text() {
      const colors = {
        'emerald': 'Positive',
        'rose': 'Negative',
        'amber': 'Neutral',
        'blue': 'Active',
        'purple': 'Pending'
      };
      return colors[this.color] || 'Active';
    }
  },
};
</script>
