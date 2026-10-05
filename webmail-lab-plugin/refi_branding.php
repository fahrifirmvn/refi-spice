<?php

class refi_branding extends rcube_plugin
{
    public $task = '.*';

    #[Override]
    public function init(): void
    {
        $this->add_hook('loginform_content', [$this, 'loginFormContent']);
        $this->include_stylesheet($this->local_skin_path().'/refi-mail.css');
        $this->include_script($this->local_skin_path().'/refi-mail.js');
    }

    /**
     * Present the native Roundcube login fields in the local Refi Mail layout.
     * Authentication, CSRF fields, validation, and submission remain native.
     *
     * @param  array{hidden: array<string, string>, inputs: array<string, array<string, string>>, buttons: array<string, array<string, string>>}  $formContent
     * @return array{hidden: array<string, string>, inputs: array<string, array<string, string>>, buttons: array<string, array<string, string>>}
     */
    public function loginFormContent(array $formContent): array
    {
        $formContent['inputs']['user']['title'] = html::label('rcmloginuser', 'Alamat email');
        $formContent['inputs']['user']['content'] = str_replace(
            'id="rcmloginuser"',
            'id="rcmloginuser" placeholder="nama@domain.com" autocomplete="username" inputmode="email"',
            $formContent['inputs']['user']['content'],
        );
        $formContent['inputs']['password']['title'] = html::label('rcmloginpwd', 'Kata sandi');
        $formContent['inputs']['password']['content'] = str_replace(
            'id="rcmloginpwd"',
            'id="rcmloginpwd" placeholder="Masukkan kata sandi" autocomplete="current-password"',
            $formContent['inputs']['password']['content'],
        );

        return $formContent;
    }
}
