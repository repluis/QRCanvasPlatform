<script setup>
const props = defineProps({
    element: { type: Object, default: null },
    totalCards: { type: Number, default: 1 },
})

const emit = defineEmits(['update'])

function emitChange(field, value) {
    if (!props.element) return
    emit('update', props.element.id, { [field]: value })
}

function updateItem(index, field, value) {
    if (!props.element) return
    const items = [...props.element.items]
    items[index] = { ...items[index], [field]: value }
    emit('update', props.element.id, { items })
}

function addItem() {
    if (!props.element) return
    const items = [...props.element.items, { label: 'New', targetCard: 0 }]
    emit('update', props.element.id, { items })
}

function removeItem(index) {
    if (!props.element) return
    const items = props.element.items.filter((_, i) => i !== index)
    emit('update', props.element.id, { items })
}
</script>

<template>
    <div v-if="element" class="w-64 border-l bg-white p-4">
        <h3 class="mb-3 text-sm font-semibold uppercase tracking-wider text-gray-500">
            Navigation properties
        </h3>

        <div class="space-y-3">
            <div>
                <label class="mb-1 block text-xs text-gray-400">Buttons</label>
                <div class="space-y-2">
                    <div
                        v-for="(item, i) in element.items"
                        :key="i"
                        class="flex items-center gap-1 rounded-lg border border-gray-200 p-2"
                    >
                        <input
                            :value="item.label"
                            class="w-20 rounded border border-gray-200 bg-white px-2 py-1 text-xs text-gray-900 outline-none focus:border-indigo-400"
                            placeholder="Label"
                            @input="updateItem(i, 'label', $event.target.value)"
                        />
                        <span class="text-xs text-gray-400">→</span>
                        <select
                            :value="item.targetCard"
                            class="flex-1 rounded border border-gray-200 bg-white px-2 py-1 text-xs text-gray-900 outline-none focus:border-indigo-400"
                            @change="updateItem(i, 'targetCard', Number($event.target.value))"
                        >
                            <option
                                v-for="c in totalCards"
                                :key="c - 1"
                                :value="c - 1"
                            >
                                Card {{ c }}
                            </option>
                        </select>
                        <button
                            class="flex h-5 w-5 items-center justify-center rounded text-xs text-gray-400 hover:bg-red-50 hover:text-red-500"
                            @click="removeItem(i)"
                        >×</button>
                    </div>
                </div>
                <button
                    class="mt-2 w-full rounded-lg border border-dashed border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-500 transition hover:border-indigo-400 hover:text-indigo-600"
                    @click="addItem"
                >
                    + Add button
                </button>
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Button color</label>
                <input
                    :value="element.buttonColor"
                    type="color"
                    class="h-8 w-full cursor-pointer rounded border border-gray-200"
                    @input="emitChange('buttonColor', $event.target.value)"
                />
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Text color</label>
                <input
                    :value="element.buttonTextColor"
                    type="color"
                    class="h-8 w-full cursor-pointer rounded border border-gray-200"
                    @input="emitChange('buttonTextColor', $event.target.value)"
                />
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Corner radius</label>
                <input
                    :value="element.borderRadius"
                    type="range"
                    min="0"
                    max="24"
                    class="w-full accent-indigo-500"
                    @input="emitChange('borderRadius', Number($event.target.value))"
                />
                <span class="text-xs text-gray-400">{{ element.borderRadius }}px</span>
            </div>

            <div>
                <label class="mb-1 block text-xs text-gray-400">Gap between buttons</label>
                <input
                    :value="element.gap"
                    type="range"
                    min="0"
                    max="30"
                    class="w-full accent-indigo-500"
                    @input="emitChange('gap', Number($event.target.value))"
                />
                <span class="text-xs text-gray-400">{{ element.gap }}px</span>
            </div>
        </div>
    </div>
</template>
