<script setup>
    import { ref } from 'vue'
    import { RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption } from '@headlessui/vue'

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { ChevronRightIcon } from '@heroicons/vue/20/solid'
    import moment from 'moment';
    import { Head, Link } from '@inertiajs/vue3';


    const settings = [
        { year: '2019', description: 'Some cool description' },
        { year: '2020', description: 'Some cool description' },
        { year: '2021', description: 'Some cool description' },
        { year: '2022', description: 'Some cool description' },
        { year: '2023', description: 'Some cool description' },
        { year: '2024', description: 'Some cool description' },

    ]

    const selected = ref(settings[0])

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Race Schedule</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="border-b border-gray-200 bg-white px-4 py-5 rounded-md mb-4 sm:px-6">
                    <div class="-ml-4 -mt-2 flex flex-wrap items-center justify-between sm:flex-nowrap">
                    <div class="ml-4 mt-2">
                        <h3 class="text-base font-semibold leading-6 text-gray-900">Selected year: {{ selected.year }}</h3>
                    </div>
                    <div class="ml-4 mt-2 flex-shrink-0">
                        <Link :href="route('schedule.show',`${selected.year}`)" type="button" class="relative inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Load data</Link>
                    </div>
                    </div>
                </div>
                <RadioGroup v-model="selected">
                    <RadioGroupLabel class="sr-only">Privacy setting</RadioGroupLabel>
                    <div class="-space-y-px rounded-md bg-white">
                    <RadioGroupOption as="template" v-for="(setting, settingIdx) in settings" :key="setting.year" :value="setting" v-slot="{ checked, active }">
                        <div :class="[settingIdx === 0 ? 'rounded-tl-md rounded-tr-md' : '', settingIdx === settings.length - 1 ? 'rounded-bl-md rounded-br-md' : '', checked ? 'z-10 border-indigo-200 bg-indigo-50' : 'border-gray-200', 'relative flex cursor-pointer border p-4 focus:outline-none']">
                        <span :class="[checked ? 'bg-indigo-600 border-transparent' : 'bg-white border-gray-300', active ? 'ring-2 ring-offset-2 ring-indigo-600' : '', 'mt-0.5 h-4 w-4 shrink-0 cursor-pointer rounded-full border flex items-center justify-center']" aria-hidden="true">
                            <span class="rounded-full bg-white w-1.5 h-1.5" />
                        </span>
                        <span class="ml-3 flex flex-col">
                            <RadioGroupLabel as="span" :class="[checked ? 'text-indigo-900' : 'text-gray-900', 'block text-sm font-medium']">{{ setting.year }}</RadioGroupLabel>
                            <RadioGroupDescription as="span" :class="[checked ? 'text-indigo-700' : 'text-gray-500', 'block text-sm']">{{ setting.description }}</RadioGroupDescription>
                        </span>
                        </div>
                    </RadioGroupOption>
                    </div>
                </RadioGroup>                
            </div>
        </div>
    </AuthenticatedLayout>
</template>
