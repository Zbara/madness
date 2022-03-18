<?php

namespace App\Model;

class UserParams
{
    var int $user_id = 0;
    var int $platform_id = 0;
    var array $app_friends;
    var string $sex;
    var string $last_name;
    var string $timezone;
    var string $country;
    var string $city;
    var string $avatar;
    var string $first_name;
    var string $birthdate;
    var string $session_key;
    var int $session_id;

    /**
     * @return string
     */
    public function getSessionKey(): string
    {
        return $this->session_key;
    }

    /**
     * @param string $session_key
     * @return UserParams
     */
    public function setSessionKey(string $session_key): UserParams
    {
        $this->session_key = $session_key;
        return $this;
    }

    /**
     * @return int
     */
    public function getSessionId(): int
    {
        return $this->session_id;
    }

    /**
     * @param int $session_id
     * @return UserParams
     */
    public function setSessionId(int $session_id): UserParams
    {
        $this->session_id = $session_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return UserParams
     */
    public function setUserId(int $user_id): UserParams
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlatformId(): int
    {
        return $this->platform_id;
    }

    /**
     * @param int $platform_id
     * @return UserParams
     */
    public function setPlatformId(int $platform_id): UserParams
    {
        $this->platform_id = $platform_id;
        return $this;
    }

    /**
     * @return array
     */
    public function getAppFriends(): array
    {
        return array_unique($this->app_friends);
    }

    /**
     * @param array $app_friends
     * @return UserParams
     */
    public function setAppFriends(array $app_friends): UserParams
    {
        $this->app_friends = $app_friends;
        return $this;
    }

    /**
     * @return string
     */
    public function getSex(): string
    {
        return $this->sex;
    }

    /**
     * @param string $sex
     * @return UserParams
     */
    public function setSex(string $sex): UserParams
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }

    /**
     * @param string $last_name
     * @return UserParams
     */
    public function setLastName(string $last_name): UserParams
    {
        $this->last_name = $last_name;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimezone(): int
    {
        return $this->timezone;
    }

    /**
     * @param int $timezone
     * @return UserParams
     */
    public function setTimezone(int $timezone): UserParams
    {
        $this->timezone = $timezone;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return UserParams
     */
    public function setCountry(string $country): UserParams
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return UserParams
     */
    public function setCity(string $city): UserParams
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * @param string $avatar
     * @return UserParams
     */
    public function setAvatar(string $avatar): UserParams
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @param string $first_name
     * @return UserParams
     */
    public function setFirstName(string $first_name): UserParams
    {
        $this->first_name = $first_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getBirthdate(): string
    {
        return $this->birthdate;
    }

    /**
     * @param string $birthdate
     * @return UserParams
     */
    public function setBirthdate(string $birthdate): UserParams
    {
        $this->birthdate = $birthdate;
        return $this;
    }
}
