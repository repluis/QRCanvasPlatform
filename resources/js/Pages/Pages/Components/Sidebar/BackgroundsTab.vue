<script setup>
import { ref } from 'vue'

const emit = defineEmits(['setBackground'])

const bgSubtab = ref('solids')
</script>

<template>
    <div class="space-y-3">
        <div class="flex flex-wrap gap-1">
            <button
                v-for="st in ['solids', 'pastels', 'gradients']"
                :key="st"
                class="rounded px-2 py-1 text-xs font-medium transition"
                :class="bgSubtab === st
                    ? 'bg-indigo-100 text-indigo-700'
                    : 'text-gray-500 hover:bg-gray-100'"
                @click="bgSubtab = st"
            >
                {{ st === 'solids' ? 'Solid' : st === 'pastels' ? 'Pastel' : 'Gradient' }}
            </button>
        </div>

        <div v-if="bgSubtab === 'solids'" class="grid grid-cols-5 gap-1.5">
            <button
                class="h-8 w-full rounded-lg border border-gray-300 transition hover:scale-110 active:scale-95 bg-transparent"
                style="background-image: linear-gradient(45deg, #ccc 25%, transparent 25%), linear-gradient(-45deg, #ccc 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #ccc 75%), linear-gradient(-45deg, transparent 75%, #ccc 75%); background-size: 8px 8px; background-position: 0 0, 0 4px, 4px -4px, -4px 0;"
                @click="emit('setBackground', 'transparent')"
                title="Transparent"
            ></button>
            <button
                v-for="c in ['#ef4444','#f97316','#eab308','#22c55e','#06b6d4','#3b82f6','#8b5cf6','#ec4899','#64748b','#1e293b','#dc2626','#ea580c','#ca8a04','#16a34a','#0891b2','#2563eb','#7c3aed','#db2777','#475569','#0f172a']"
                :key="c"
                class="h-8 w-full rounded-lg border border-gray-200 transition hover:scale-110 active:scale-95"
                :style="{ backgroundColor: c }"
                @click="emit('setBackground', c)"
                :title="c"
            ></button>
        </div>

        <div v-if="bgSubtab === 'pastels'" class="grid grid-cols-5 gap-1.5">
            <button
                v-for="c in ['#fce4ec','#f3e5f5','#e8eaf6','#e3f2fd','#e0f7fa','#e0f2f1','#e8f5e9','#fff9c4','#fff3e0','#fbe9e7','#fce4ec','#f1f8e9','#fff8e1','#e1f5fe','#f3e5f5']"
                :key="c"
                class="h-8 w-full rounded-lg border border-gray-200 transition hover:scale-110 active:scale-95"
                :style="{ backgroundColor: c }"
                @click="emit('setBackground', c)"
                :title="c"
            ></button>
        </div>

        <div v-if="bgSubtab === 'gradients'" class="space-y-2">
            <button
                v-for="g in [{l:'Purple Blue',v:'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'},{l:'Pink Red',v:'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)'},{l:'Blue Cyan',v:'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'},{l:'Green Teal',v:'linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)'},{l:'Pink Yellow',v:'linear-gradient(135deg, #fa709a 0%, #fee140 100%)'},{l:'Lavender',v:'linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%)'},{l:'Peach',v:'linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%)'},{l:'Sky Blue',v:'linear-gradient(135deg, #89f7fe 0%, #66a6ff 100%)'},{l:'Cream Sky',v:'linear-gradient(135deg, #fddb92 0%, #d1fdff 100%)'},{l:'Silver',v:'linear-gradient(135deg, #c3cfe2 0%, #f5f7fa 100%)'},{l:'Sunset',v:'linear-gradient(135deg, #fad0c4 0%, #ffd1ff 100%)'},{l:'Ocean',v:'linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%)'}]"
                :key="g.l"
                class="h-12 w-full rounded-lg border border-gray-200 transition hover:scale-[1.02] active:scale-95 flex items-center justify-center"
                :style="{ background: g.v }"
                @click="emit('setBackground', g.v)"
                :title="g.l"
            >
                <span class="text-xs font-medium drop-shadow-md"
                    :class="g.l === 'Cream Sky' || g.l === 'Silver' ? 'text-gray-700' : 'text-white'"
                >{{ g.l }}</span>
            </button>
        </div>
    </div>
</template>
