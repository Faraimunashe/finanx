<template>
    <div :class="['bg-white p-5 shadow-md rounded-xl flex items-center space-x-4 border-l-4', color_border]">
        <div :class="[icon_color, 'text-3xl']">
            <i :class="['fas', icon]"></i>
        </div>
        <div>
            <h3 class="text-gray-500 text-sm uppercase tracking-wide">{{ label }}</h3>
            <p class="text-xl font-bold text-gray-800">{{ value }}</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "Card",
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
        color_border() {
            return `border-${this.color}-500`;
        },
    },
};
</script>
