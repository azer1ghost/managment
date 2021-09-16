<?php

if (! function_exists('image')) {
    function image($url): string
    {
        $noPhotoBase64 = 'iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAM1BMVEXd3d2ampqXl
        5e9vb3g4ODS0tKUlJTPz8+/v7/W1tavr6/IyMinp6fFxcWsrKyfn5+3t7dEyn0RAAADn0lEQVR4nO2c65qqMAwApYYKKov
        v/7RH96xQoBV0S2878xuJ8wEhKW0PBwAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
        AAIBNSGhC69XdMSxdHVJSul6r0Oi+C6YoX7qKgf4KpChfKopgVakwitLFuYI
        PdJAbVfpoglXVhzCs413C+0Ws9xeULtZT+EAFuE2lMQwDvSiMgMcAhschoOoD
        ve17FcdQ3YJVbDcVxTBIXvsJ2scwDBFsCHoLGDSOYcigGOYfFMNfnt3ezJdi
        KFJfmuZiaebLMJTT+acKrM6n2alLMJT2qsfaTF/byckLMLx3LJOWRU2biPwNpVk0n
        boxTp+9oXVgxByxyN6wtTbVqt01qJMdgsnVbngdAuRueHKM/OjTjkHd+A8mZ8fIjz
        o/I+RuaPd7UIhh7Ry8U8+xw7wN5eI2vMhOQV/+Ie+GjduwwXAPuEvfp/hM8wfeFuW
        /8cuv2v5A5Z1597Rluk/OHbAcjr3W17Uvt/mOYkhdfR+tr2sH5joSNTxgRtZwnDbT
        0cRxYsr6ZJ8cR4Qnb4Hx9e0+PrdR/Vmtom5bMlNOX2YW+VFvUfxlUB9sDSbLWmzy
        AtglqBe2BrPVKR9PvEvScOn3ULx89gcTNHQU05Wavwd8BvXEpmDjJKaFYvvJX0zO
        0FJmjrTWX6zk5cQMLWnUxPaD4+3lE5qaob3dG+7Tfn7Gw70kVS+L89QMV6ZJz6rw
        Z/+xMH8vqD9Wg7nSqENxHC590X8kZehOo4biWIXLcXxm3YopGW5bqzAoytk83Nli
        JWQop/Ur+KMo3zlmerir/0jI8HUaNV36rq5van64o/9IyPCN1SbTSfiDov3eT8XQx
        4ooa/+RiuGWNLpB0VLdJGLoa8mXXvYfaRiK+zvgmyxbrDQM7T3vZ4rzFisNQ6+L9t
        qNQQMael5Y2m8KGtLQWO/lhVmjEd9QLr6XXU6r8OiG/tKoQzG6occ0aigajUZsw/W
        e9zNFs4uMauicLfJrxaHRiGtodume0UnMa/OfRk3FTqxBQxrK5p73M8X/jUbUa7in
        X/VsNCIa7pRGDVQtMQ13S6Om4r3RiGb48guMP9p4hnumUZNYhs2+aXRE9cNc8MDXMN
        hGNeoaxbAKdAUnscIaxgBDDDHEEEMMMcQQQwwxLMaw+L0vy9+/9A/sQVv+PsLl7wV
        9KH8/70P5e7Ifyt9X/79kYMLqAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAQL78AzYYQhHIq/FbAAAAAElFTkSuQmCC';

        return Storage::exists($url) ? Storage::url($url) : "data:image/png;base64, $noPhotoBase64";
    }
}

if (! function_exists('notify')) {
    function notify(): object
    {
        return new class (){
            protected function message( $color, $title, $message, $custom, $data = null): array
            {
                return  [
                    'notify' =>
                        [
                            'title' => $title,
                            'type' => $color,
                            'message' => "<b> $data </b>" . (!$custom ? $message : '')
                        ]
                ];
            }

            public function success($custom, $data = 'Record'): array
            {
                return $this->message('green', 'Successfully', 'processed successfully', $custom, $data);
            }

            public function info($custom, $data = 'Record'): array
            {
                return $this->message('blue', 'Successfully', 'processed successfully', $custom, $data);
            }

            public function error($custom): array
            {
                return $this->message('red', 'Ops... !', 'Something went wrong.', $custom);
            }
        };
    }
}

if(! function_exists('str_title')){
    function str_title($str): string {
        return Str::title(Str::replace('_', ' ', ' '.$str));
    }
}

if(! function_exists('syncResolver')){
    function syncResolver($params, $column): array {
        $array = [];
        foreach ($params as $key => $param)
        {
            if ($key == 8) {
                $array[$key] = [$column => phone_cleaner($param)];
            } elseif ($key == 6) {
                $array[$key] = [$column => pattern_adder('MBX', $param)];
            } else {
                $array[$key] = [$column => $param];
            }
        }
        return $array;
    }
}

if(! function_exists('phone_cleaner')){
    function phone_cleaner($phone = null): ?string {
        return substr(str_replace([' ', '-', '(', ')', '+'],'', $phone), -9, 9);
    }
}

if(! function_exists('phone_formatter')){
    function phone_formatter($phone, $countryCode = false, $parentheses = false): ?string {

        $firstPattern = $countryCode ? '+994 ' : 0;
        $firstPattern = $parentheses ? "({$firstPattern})" : $firstPattern;

        if(  preg_match( '/^(\d{2})(\d{3})(\d{2})(\d{2})$/', phone_cleaner($phone),  $matches ) )
        {
            return $firstPattern . $matches[1] . '-' .$matches[2] . '-' . $matches[3] . '-' . $matches[4];
        }
        return $phone;
    }
}

if(! function_exists('pattern_adder')){
    function pattern_adder($pattern, $value): ?string {
        return mb_strtoupper($pattern).preg_replace("/[^0-9]/", "", $value);
    }
}

if(! function_exists('implode_key_values')){
    function implode_key_values($array): ?string {
        return implode(', ', array_map(function ($a, $b) { return str_title($a) . " => $b"; },
            array_keys($array), array_values($array)));
    }
}