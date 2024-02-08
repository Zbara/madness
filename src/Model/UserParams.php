<?php

namespace App\Model;

use App\Entity\Users;
use Symfony\Component\Validator\Constraints as Assert;

class UserParams
{
    private Users $user;
    private int $platform_id = 0;
    private array $app_friends;
    private string $sex;
    private string $last_name;
    private string $timezone;
    private string $country;
    private string $city;
    private string $avatar;
    private string $first_name;
    private string $birthdate;
    private string $session_key;
    private int $session_id = 0;
    private int $boss_id = 0;
    private int $cell_id = 0;
    private int $job_id = 0;
    private int $collection_id = 0;

    /**
     * @return int
     */
    public function getCollectionId(): int
    {
        return $this->collection_id;
    }

    /**
     * @param int $collection_id
     * @return UserParams
     */
    public function setCollectionId(int $collection_id): UserParams
    {
        $this->collection_id = $collection_id;
        return $this;
    }

    /**
     * @return int
     */
    public function getJobId(): int
    {
        return $this->job_id;
    }

    /**
     * @param int $job_id
     * @return UserParams
     */
    public function setJobId(int $job_id): UserParams
    {
        $this->job_id = $job_id;
        return $this;
    }
    private string $name;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return UserParams
     */
    public function setName(string $name): UserParams
    {
        $this->name = $name;
        return $this;
    }


    /**
     * @return int
     */
    public function getBossId(): int
    {
        return $this->boss_id;
    }

    /**
     * @return int
     */
    public function getCellId(): int
    {
        return $this->cell_id;
    }

    /**
     * @param int $cell_id
     * @return UserParams
     */
    public function setCellId(int $cell_id): UserParams
    {
        $this->cell_id = $cell_id;
        return $this;
    }

    /**
     * @param int $boss_id
     * @return UserParams
     */
    public function setBossId(int $boss_id): UserParams
    {
        $this->boss_id = $boss_id;
        return $this;
    }

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
     * @return Users
     */
    public function getUser(): Users
    {
        return $this->user;
    }

    public function setUser(Users $user): UserParams
    {
        $this->user = $user;

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
