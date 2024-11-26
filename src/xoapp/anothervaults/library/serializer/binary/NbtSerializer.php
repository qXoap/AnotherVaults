<?php

namespace xoapp\anothervaults\library\serializer\binary;

use pocketmine\nbt\tag\Tag;
use pocketmine\nbt\TreeRoot;
use pocketmine\nbt\BigEndianNbtSerializer;

use function zlib_decode;
use function zlib_encode;
use function base64_encode;
use function base64_decode;
use const ZLIB_ENCODING_GZIP;

final class NbtSerializer
{

    public static function toBinary(Tag $tag, bool $z_gip = false): string
    {
        $contents = (new BigEndianNbtSerializer())->write(new TreeRoot($tag));
        return $z_gip ? zlib_encode($contents, ZLIB_ENCODING_GZIP) : $contents;
    }

    public static function fromBinary(string $contents, bool $z_gip = false): Tag
    {
        return (new BigEndianNbtSerializer())->read($z_gip ? zlib_decode($contents) : $contents)->getTag();
    }

    public static function toBase64(Tag $tag, bool $z_gip = false): string
    {
        return base64_encode(self::toBinary($tag, $z_gip));
    }

    public static function fromBase64(string $contents, bool $z_gip = false): Tag
    {
        return self::fromBinary(base64_decode($contents), $z_gip);
    }
}