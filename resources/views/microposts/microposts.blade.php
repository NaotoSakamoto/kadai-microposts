@if (count($microposts) > 0)
    <ul class="list-unstyled">
        @foreach ($microposts as $micropost)
            <li class="media mb-3">
                {{-- 投稿の所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                <img class="mr-2 rounded" src="{{ Gravatar::get($micropost->user->email, ['size' => 50]) }}" alt="">
                <div class="media-body">
                    <div>
                        {{-- 投稿の所有者のユーザ詳細ページへのリンク --}}
                        {!! link_to_route('users.show', $micropost->user->name, ['user' => $micropost->user->id]) !!}
                        <span class="text-muted">posted at {{ $micropost->created_at }}</span>
                    </div>
                    <div>
                        {{-- 投稿内容 --}}
                        <p class="mb-0">{!! nl2br(e($micropost->content)) !!}</p>
                    </div>
                    <div>
                        @if (Auth::id() == $micropost->user_id)
                            {{-- 投稿削除ボタンのフォーム --}}
                            {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        @elseif (\Auth::user()->is_admin==1)
                            {{-- 投稿削除ボタンのフォーム --}}
                            {!! Form::open(['route' => ['microposts.destroy', $micropost->id], 'method' => 'delete']) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                    <div>
                        @if (Auth::user()->is_favoriting($micropost->id))
                            {{-- お気に入り解除ボタンのフォーム --}}
                            {!! Form::open(['route' => ['favorites.unfavorite', $micropost->id], 'method' => 'delete']) !!}
                                {!! Form::submit('Unfavorite', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        @else
                            {{-- お気に入りボタンのフォーム --}}
                            {!! Form::open(['route' => ['favorites.favorite', $micropost->id]]) !!}
                                {!! Form::submit('Favorite',) !!}
                            {!! Form::close() !!}
                        @endif
                    </div>
                    @if (Auth::id() == $micropost->user_id)
                        {{-- 編集ボタンのフォーム --}}
                        {!! link_to_route('microposts.edit', 'Edit', ['micropost' => $micropost->id], ['class' => 'btn btn-light']) !!}
                    @elseif (Auth::user()->is_admin==1)
                        {{-- 編集ボタンのフォーム --}}
                        {!! link_to_route('microposts.edit', 'Edit', ['micropost' => $micropost->id], ['class' => 'btn btn-light']) !!}                
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
    {{-- ページネーションのリンク --}}
    {{ $microposts->links() }}
@endif