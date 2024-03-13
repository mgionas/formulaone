<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, router } from '@inertiajs/vue3';

    const props = defineProps({
        session:{
            type:Object
        }
    })

    const storeSessionData = () => {
        router.post(route('storeSessionData'), {
            meetingKey:props.session.info.Meeting.Key,
            meetingName:props.session.info.Meeting.Name,
            meetingLocation:props.session.info.Meeting.Location,
            meetingCountry:props.session.info.Meeting.Country.Name,
            raceId:props.session.info.Key,
            type:props.session.info.Type,
            name:props.session.info.Name,
            startDate:props.session.info.StartDate,
            endDate:props.session.info.EndDate,
            driversData:props.session.results,
        })
    }
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Session</h2>
        </template>

        <div class="py-12">
            <div class="flex gap-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex w-2/3 rounded-md">
                    <ul role="list" class="w-full grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2">
                        <li v-for="pilot in session.results" :key="pilot.DriverNumber" class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow">
                            <div class="flex w-full items-center justify-between space-x-6 p-6">
                                <div class="flex-1 truncate">
                                <div class="flex items-center space-x-3">
                                    <h3 class="truncate text-sm font-medium text-gray-900">{{ pilot.FullName }}</h3>
                                    <span class="inline-flex flex-shrink-0 items-center rounded-full bg-green-50 px-1.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">{{ pilot.DriverNumber }}</span>
                                </div>
                                <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.TeamName }}</p>
                                </div>
                                <img :class="`bg-[#${pilot.TeamColor}]`" class="h-10 w-10 flex-shrink-0 rounded-full" :src="pilot.HeadshotUrl" alt="" />
                            </div>
                            <div>
                                <div class="flex flex-col px-6 py-4 gap-2">
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">Abbreviation:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.Abbreviation }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">CountryCode:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.CountryCode }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">Position:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.Position }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">ClassifiedPosition:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.ClassifiedPosition }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">GridPosition:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.GridPosition }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">Q1:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.Q1 }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">Q2:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.Q2 }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">Q3:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.Q3 }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">Time:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.Time }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">Status:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.Status }}</p>
                                    </div>
                                    <div class="flex justify-between">
                                        <p class="mt-1 truncate text-sm text-gray-500">Points:</p>
                                        <p class="mt-1 truncate text-sm text-gray-500">{{ pilot.Points }}</p>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="flex flex-col gap-2 w-1/3 p-4 rounded-md bg-white">
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-md text-red-500">{{ session.info.Meeting.OfficialName }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">Key:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.Meeting.Key }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">Name:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.Meeting.Name }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">Location:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.Meeting.Location }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">Country:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.Meeting.Country.Name }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">Circuit:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.Meeting.Circuit.ShortName }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-md text-red-500">Details:</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">ArchiveStatus:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.ArchiveStatus.Status }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">Key:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.Key }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">Type:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.Type }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">StartDate:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.StartDate }}</p>
                    </div>
                    <div class="flex w-full justify-between">
                        <p class="mt-1 truncate text-sm text-gray-500">EndDate:</p>
                        <p class="mt-1 truncate text-sm text-gray-500">{{ session.info.EndDate }}</p>
                    </div>
                    <div class="h-4"></div>
                    <button @click="storeSessionData" type="button" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Add to database</button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
