@component('mail::message')
# Introduction

You hav been invited to join the team
**{{ $invitation->team->name }}**.
Beacause you are alredy registerd to the platform, you just
need to accept or reject the invitation in your
[Team managaement console]({{ $url }}).

@component('mail::button', ['url' => $url])
Go to dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
