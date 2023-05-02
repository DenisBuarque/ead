@foreach ($directchat->direct_chat_messages as $message)
    @if ($message->user_id == auth()->user()->id)
        <div class="direct-chat-msg">
            <div class="direct-chat-infos clearfix">
                <span
                    class="direct-chat-name float-left">{{ \Illuminate\Support\Str::substr($message->user->name, 0, 15) }}...</span>
                <span
                    class="direct-chat-timestamp float-right">{{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:m:s') }}</span>
            </div>
            @if (isset($message->user->image))
                <img class="direct-chat-img" src="{{ asset('storage/' . $message->user->image) }}" alt="Image">
            @else
                <img class="direct-chat-img" src="/images/not-photo.jpg" alt="Image">
            @endif
            <div class="direct-chat-text">
                {{ $message->message }}
            </div>
        </div>
    @else
        <div class="direct-chat-msg right">
            <div class="direct-chat-infos clearfix">
                <span
                    class="direct-chat-name float-right">{{ \Illuminate\Support\Str::substr($message->user->name, 0, 15) }}...</span>
                <span
                    class="direct-chat-timestamp float-left">{{ \Carbon\Carbon::parse($message->created_at)->format('d/m/Y H:m:s') }}</span>
            </div>
            @if (isset($message->user->image))
                <img class="direct-chat-img" src="{{ asset('storage/' . $message->user->image) }}" alt="Image">
            @else
                <img class="direct-chat-img" src="/images/not-photo.jpg" alt="Image">
            @endif
            <div class="direct-chat-text">
                {{ $message->message }}
            </div>
        </div>
    @endif
@endforeach
