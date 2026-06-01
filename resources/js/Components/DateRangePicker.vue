<template>
    <div class="date-range-picker bg-white rounded-lg shadow-sm border border-gray-200 p-2.5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <!-- Header & Display -->
            <div class="flex items-center justify-between sm:justify-start">
                <div class="flex items-center">
                    <div class="p-1 bg-indigo-50 rounded mr-2">
                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 whitespace-nowrap hidden sm:inline-block mr-3">{{ label }}</span>
                </div>
                
                <span v-if="activePreset !== 'custom'" class="text-[10px] text-indigo-600 font-medium bg-indigo-50 px-1.5 py-0.5 rounded whitespace-nowrap">
                    {{ displayRange }}
                </span>
            </div>

            <!-- Preset Dropdown -->
            <div class="flex items-center flex-1 sm:justify-end mt-2 sm:mt-0">
                <div class="relative w-full sm:w-auto min-w-[140px]">
                    <select
                        v-model="activePreset"
                        @change="selectPreset(activePreset)"
                        class="w-full px-3 py-1.5 text-xs font-medium bg-gray-50 border border-gray-200 rounded cursor-pointer text-gray-700 hover:bg-white hover:border-gray-300 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    >
                        <option v-for="preset in presets" :key="preset.key" :value="preset.key">
                            {{ preset.label }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Custom Date Inputs (shown only when "Custom" selected) -->
        <div v-if="activePreset === 'custom'" class="flex flex-col sm:flex-row items-center gap-2 mt-2 pt-2 border-t border-gray-100">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="text-[10px] font-medium text-gray-500 whitespace-nowrap w-12">{{ startLabel }}:</label>
                <input
                    v-model="localStart"
                    type="date"
                    @change="emitCustomRange"
                    class="flex-1 px-2 py-1 text-xs bg-white border border-gray-300 rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <label class="text-[10px] font-medium text-gray-500 whitespace-nowrap w-12">{{ endLabel }}:</label>
                <input
                    v-model="localEnd"
                    type="date"
                    @change="emitCustomRange"
                    class="flex-1 px-2 py-1 text-xs bg-white border border-gray-300 rounded focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from "vue";

const props = defineProps<{
    startDate?: string;
    endDate?: string;
    language?: string;
    label?: string;
    startLabel?: string;
    endLabel?: string;
}>();

const emit = defineEmits<{
    (e: "update:startDate", val: string): void;
    (e: "update:endDate", val: string): void;
    (e: "change"): void;
}>();

const activePreset = ref("today");
const localStart = ref("");
const localEnd = ref("");

// ---- Date helpers ----
function toISO(d: Date) {
    const y = d.getFullYear();
    const m = String(d.getMonth() + 1).padStart(2, "0");
    const day = String(d.getDate()).padStart(2, "0");
    return `${y}-${m}-${day}`;
}

function getMonday(d: Date) {
    const day = d.getDay() || 7; // treat sunday as 7
    const result = new Date(d);
    result.setDate(d.getDate() - (day - 1));
    return result;
}

function computeRange(key: string): { start: string; end: string } {
    const now = new Date();
    const today = toISO(now);

    switch (key) {
        case "today":
            return { start: today, end: today };

        case "last_week": {
            const mon = getMonday(now);
            mon.setDate(mon.getDate() - 7);
            const sun = new Date(mon);
            sun.setDate(mon.getDate() + 6);
            return { start: toISO(mon), end: toISO(sun) };
        }

        case "this_week": {
            const mon = getMonday(now);
            return { start: toISO(mon), end: today };
        }

        case "last_month": {
            const first = new Date(now.getFullYear(), now.getMonth() - 1, 1);
            const last = new Date(now.getFullYear(), now.getMonth(), 0);
            return { start: toISO(first), end: toISO(last) };
        }

        case "this_month": {
            const first = new Date(now.getFullYear(), now.getMonth(), 1);
            return { start: toISO(first), end: today };
        }

        case "last_year": {
            const first = new Date(now.getFullYear() - 1, 0, 1);
            const last = new Date(now.getFullYear() - 1, 11, 31);
            return { start: toISO(first), end: toISO(last) };
        }

        case "this_year": {
            const first = new Date(now.getFullYear(), 0, 1);
            return { start: toISO(first), end: today };
        }

        default:
            return { start: localStart.value, end: localEnd.value };
    }
}

// ---- Presets config (language-aware) ----
const lang = computed(() => props.language || "en");

const presets = computed(() => {
    const isEN = lang.value === "en";
    return [
        { key: "today",      label: isEN ? "Today"      : "আজ"          },
        { key: "last_week",  label: isEN ? "Last Week"  : "গত সপ্তাহ"   },
        { key: "this_week",  label: isEN ? "This Week"  : "এই সপ্তাহ"   },
        { key: "last_month", label: isEN ? "Last Month" : "গত মাস"      },
        { key: "this_month", label: isEN ? "This Month" : "এই মাস"      },
        { key: "last_year",  label: isEN ? "Last Year"  : "গত বছর"      },
        { key: "this_year",  label: isEN ? "This Year"  : "এই বছর"      },
        { key: "custom",     label: isEN ? "Custom"     : "কাস্টম"      },
    ];
});

const label = computed(() =>
    props.label ?? (lang.value === "en" ? "Date Range" : "তারিখ পরিসর")
);
const startLabel = computed(() =>
    props.startLabel ?? (lang.value === "en" ? "Start Date" : "শুরুর তারিখ")
);
const endLabel = computed(() =>
    props.endLabel ?? (lang.value === "en" ? "End Date" : "শেষের তারিখ")
);

const displayRange = computed(() => {
    const { start, end } = computeRange(activePreset.value);
    if (!start && !end) return "";
    if (start === end) return start;
    return `${start}  →  ${end}`;
});

function selectPreset(key: string) {
    activePreset.value = key;
    if (key !== "custom") {
        const { start, end } = computeRange(key);
        localStart.value = start;
        localEnd.value = end;
        emit("update:startDate", start);
        emit("update:endDate", end);
        emit("change");
    } else {
        // for custom, keep current values or default to today
        if (!localStart.value) localStart.value = toISO(new Date());
        if (!localEnd.value) localEnd.value = toISO(new Date());
    }
}

function emitCustomRange() {
    emit("update:startDate", localStart.value);
    emit("update:endDate", localEnd.value);
    emit("change");
}

function resolvePreset(start: string, end: string) {
    if (!start || !end) return "today";

    const presetKeys = [
        "today",
        "last_week",
        "this_week",
        "last_month",
        "this_month",
        "last_year",
        "this_year",
    ];

    for (const key of presetKeys) {
        const range = computeRange(key);
        if (range.start === start && range.end === end) {
            return key;
        }
    }

    return "custom";
}

// If parent passes initial values, sync
watch(
    () => [props.startDate, props.endDate],
    ([s, e]) => {
        if (s && e) {
            localStart.value = s;
            localEnd.value = e;
            activePreset.value = resolvePreset(s, e);
        }
    },
    { immediate: true }
);

onMounted(() => {
    if (props.startDate && props.endDate) {
        localStart.value = props.startDate;
        localEnd.value = props.endDate;
        activePreset.value = resolvePreset(props.startDate, props.endDate);
        return;
    }

    selectPreset("today");
});
</script>

<style scoped>
.date-range-picker {
    user-select: none;
}
</style>
