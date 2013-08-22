<div class="row-fluid">
    <div class="well">
        <div class="well-title">Memberlist</div>
        <table class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th class="text-center">Status</th>
                    <th>Last Active</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>{{ HTML::link('profile/user/'. $user->id, $user->username) }}</td>
                        <td>{{ $user->email }}</td>
                        <td class="text-center">
                            {{ ($user->lastActive >= date('Y-m-d H:i:s', strtotime('-15 minutes'))
                                ? HTML::image('img/icons/online.png', 'Online', array('title' => 'Online'))
                                : HTML::image('img/icons/offline.png', 'Offline', array('title' => 'Offline'))
                            ) }}
                        </td>
                        <td>{{ $user->lastActiveReadable }}</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>