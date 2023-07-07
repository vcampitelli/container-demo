<?php

namespace App\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionException;

/**
 * Main container class
 */
class Container implements ContainerInterface
{

    /**
     * Holds all factories
     *
     * @var array
     */
    private array $factories = [];

    /**
     * Aliases mapping for classes. Useful to use to provide real implementations for interfaces.
     *
     * @var array
     */
    private array $aliases = [];

    /**
     * Custom scalar definitions to inject values into built-in arguments (e.g. string, int).
     *
     * @var array
     */
    private array $settings = [];

    /**
     * Constructor
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['factories'])) {
            $this->factories = (array) $config['factories'];
        }

        if (isset($config['aliases'])) {
            $this->aliases = (array) $config['aliases'];
        }

        if (isset($config['settings'])) {
            $this->settings = (array) $config['settings'];
        }
    }

    /**
     * Creates an object for the specified identifier
     *
     * @param string $id
     *
     * @return object
     * @throws NotFoundException
     */
    public function get(string $id)
    {
        // If there's an alias for this service, we'll build it
        if (isset($this->aliases[$id])) {
            $realClassName = $this->aliases[$id];
            return $this->get($realClassName);
        }

        // If there's a factory associated to this service...
        if (isset($this->factories[$id])) {
            return $this->getFromFactory($id);
        }

        // If there's a setting associated to this ID...
        if (isset($this->settings[$id])) {
            return $this->settings[$id];
        }

        if (!\class_exists($id)) {
            throw new NotFoundException("Class doesn't exist", $id);
        }

        return $this->getFromReflection($id);
    }

    /**
     * Creates a class using its factory
     *
     * @param string $id
     *
     * @return object
     * @throws NotFoundException
     */
    private function getFromFactory(string $id): object
    {
        $factoryOrClassName = $this->factories[$id];

        // If the factory is a function or an invokable class, we just call it
        if (\is_callable($factoryOrClassName)) {
            return $factoryOrClassName($this);
        }

        // If it's a string instead, then it's the name of a factory
        if ((\is_string($factoryOrClassName)) && (\class_exists($factoryOrClassName))) {
            $factory = new $factoryOrClassName();

            // If the class implements __invoke() ...
            if (\is_callable($factory)) {
                // We override config with the actual instance to save some processing in next calls
                $this->factories[$id] = $factory;
                return $factory($this);
            }

            // If not, we remove it from the factories config to avoid doing this check again in the future
            unset($this->factories[$id]);
        }

        throw new NotFoundException(
            'No valid factory associated',
            $id
        );
    }

    /**
     * Reads the constructor to automatically inject dependencies.
     *
     * This has some performance drawbacks so in real case scenarios we should cache the response.
     *
     * @param string $id
     *
     * @return object
     * @throws NotFoundException
     */
    private function getFromReflection(string $id): object
    {
        try {
            $reflection = new ReflectionClass($id);
        } catch (ReflectionException $exception) {
            throw new NotFoundException(
                "Can't instantiate ReflectionClass: {$exception->getMessage()}",
                $id,
                $exception
            );
        }

        $args = [];
        $constructor = $reflection->getConstructor();
        if (isset($constructor)) {
            foreach ($constructor->getParameters() as $parameter) {
                // If this is a scalar type (e.g. string, int), we'll use the parameter name to retrieve the value
                // from the Container. Otherwise, we'll use the class name.
                $argumentId = ($parameter->getType()->isBuiltin())
                    ? \strtolower($parameter->getName())
                    : $parameter->getType()->getName();

                // Fetching instances from this container. This could generate a recursion loop.
                $args[] = $this->get($argumentId);
            }
        }

        // If we don't have arguments, creating it directly via `new` is way faster
        if (empty($args)) {
            return new $id;
        }

        return $reflection->newInstanceArgs($args);
    }

    /**
     * Returns if this container can create the specified service
     *
     * @param string $id
     *
     * @return bool
     */
    public function has(string $id): bool
    {
        return isset($this->factories[$id]) || isset($this->aliases[$id]) || \class_exists($id);
    }

}
