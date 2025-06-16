@component('mail::message')

{{-- Asset Assignment Confirmation --}}

Hi {{ $serialNumber->user->name }},

You have been assigned the following asset:

- **Asset:** {{ $serialNumber->item->name }}
-**Serial Number:** {{ $serialNumber->serial_number }}

Please find the attached delivery note and agreement.

Thanks and Kind Regards,
{{ config('app.name') }}

@endcomponent
