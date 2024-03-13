<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { ChevronRightIcon } from '@heroicons/vue/20/solid'
    import moment from 'moment';
    import { Head, Link } from '@inertiajs/vue3';

    const props = defineProps({
        raceSchedule:{
            type:Object
        }
    })

    function formatIsoToDDMMYYHH(isoString) {
        const date = new Date(isoString);
        const format = moment(date).utcOffset('+0400').format('LL HH:mm')

        return `${format}`;
    }

    function getYear(isoString){
        const date = new Date(isoString);
        const format = moment(date).utcOffset('+0400').year()

        return `${format}`;
    }

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Race Schedule</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <ul role="list" class="divide-y divide-gray-100 overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl">
                    <li v-for="(race, index) in raceSchedule" :key="race.email" class="relative flex flex-col hover:bg-gray-50 sm:px-6">
                        <div class="frelative flex justify-between gap-x-6 px-4 py-5 ">
                            <div class="flex min-w-0 gap-x-4">
                            <div class="h-12 w-12 flex items-center justify-center font-black rounded-full bg-gray-50">
                                {{ index + 1 }}
                            </div>
                            <div class="min-w-0 flex-auto">
                            <div class="flex items-center justify-center gap-2 text-sm font-semibold leading-6 text-gray-900">
                                <div v-if="race.F1ApiSupport" class="flex items-center justify-center rounded-full bg-green-500/20 p-1 h-4 w-4">
                                    <div class="h-1.5 w-1.5 rounded-full bg-green-500" />
                                </div>
                                <div v-else class="flex items-center justify-center rounded-full bg-pink-500/20 p-1 h-4 w-4">
                                    <div class="h-1.5 w-1.5 rounded-full bg-pink-500" />
                                </div>
                                <span class="absolute inset-x-0 -top-px bottom-0" />
                                {{ race.OfficialEventName }}
                            </div>
                            <p class="mt-1 flex flex-col text-xs leading-5 text-gray-500">
                                <div class="relative truncate hover:underline">{{ race.Country }} | {{ race.Location }} | {{ race.EventName }}</div>
                            </p>
                            </div>
                        </div>
                        <div class="flex shrink-0 items-center gap-x-4">
                            <div class="hidden sm:flex sm:flex-col sm:items-end">
                            <p class="text-sm leading-6 text-gray-900">{{ formatIsoToDDMMYYHH(race.EventDate) }}</p>
                            <p class="text-xs leading-5 text-gray-500">Round: {{ race.RoundNumber }}</p>
                            </div>
                        </div>
                        </div>
                        <div class="flex my-5 rounded-md py-2 px-2 border">
                            <ul role="list" class="space-y-1 w-full">
                                <template v-for="(n, index) in 5" :key="n">
                                    <li v-if="race[`Session${n}`] != 'None'" class="relative flex flex-col gap-y-4 w-full rounded-md py-4 transition-all hover:bg-gray-100 hover:pr-2">
                                        <Link :href="new Date(race[`Session${n}Date`]).setHours(0,0,0,0) < new Date().setHours(0,0,0,0)?route('getSession',[getYear(race.EventDate),race.OfficialEventName,race[`Session${n}`]]):route('getFutureSession',[2024,race.OfficialEventName,race[`Session${n}`]])" class="flex w-full justify-between items-center">
                                            <div class="flex w-1/3">
                                                <div class="relative flex h-6 w-6 flex-none items-center justify-center">
                                                    <div class="h-1.5 w-1.5 rounded-full bg-gray-100 ring-1 ring-gray-300"></div>
                                                </div>
                                                <p class="flex-auto py-0.5 text-xs leading-5 text-gray-500">
                                                    <span class="font-medium text-gray-900">{{ race[`Session${n}`] }}</span>
                                                </p>
                                            </div>
                                            <div class="flex w-1/3 justify-center">
                                                <time class="flex py-0.5 text-xs leading-5 text-gray-500">
                                                    {{ formatIsoToDDMMYYHH(race[`Session${n}Date`]) }}
                                                </time>
                                            </div>
                                            <div class="flex items-center justify-end w-1/3">
                                                <div v-if="new Date(race[`Session${n}Date`]).setHours(0,0,0,0) < new Date().setHours(0,0,0,0)" class="mt-1 flex items-center gap-x-1.5">
                                                    <div class="flex-none rounded-full bg-blue-500/20 p-1">
                                                        <div class="h-1.5 w-1.5 rounded-full bg-blue-500" />
                                                    </div>
                                                    <p class="text-xs leading-5 text-gray-500">Finished</p>
                                                </div>
                                                <div v-else-if="new Date(race[`Session${n}Date`]).setHours(0,0,0,0) === new Date().setHours(0,0,0,0) && new Date() >= new Date(race[`Session${n}Date`]) && new Date() <= new Date(new Date(race[`Session${n}Date`]).getTime() + (2*60*60*1000))" class="mt-1 flex items-center gap-x-1.5">
                                                    <div class="flex-none rounded-full bg-pink-500/20 p-1">
                                                        <div class="h-1.5 w-1.5 rounded-full bg-pink-500" />
                                                    </div>
                                                    <p class="text-xs leading-5 text-gray-500">Live</p>
                                                </div>
                                                <div v-else-if="new Date(race[`Session${n}Date`]).setHours(0,0,0,0) === new Date().setHours(0,0,0,0)" class="mt-1 flex items-center gap-x-1.5">
                                                    <div class="flex-none rounded-full bg-green-500/20 p-1">
                                                        <div class="h-1.5 w-1.5 rounded-full bg-green-500" />
                                                    </div>
                                                    <p class="text-xs leading-5 text-gray-500">Today</p>
                                                </div>
                                                <div v-else class="mt-1 flex items-center gap-x-1.5">
                                                    <div class="flex-none rounded-full bg-yellow-500/20 p-1">
                                                        <div class="h-1.5 w-1.5 rounded-full bg-yellow-500" />
                                                    </div>
                                                    <p class="text-xs leading-5 text-gray-500">Upcoming</p>
                                                </div>
                                                <ChevronRightIcon class="ml-4 h-5 w-5 flex-none text-gray-400" aria-hidden="true" />
                                            </div>
                                        </Link>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
